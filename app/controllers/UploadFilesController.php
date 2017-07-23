<?php

namespace TestPhalconApi\Controllers;

use TestPhalconApi\Support\Helper;
use TestPhalconApi\Models\UploadFile;
use TestPhalconApi\Exceptions\ApiException;

use \PHPExcel;
use \PHPExcel_Settings;
use \PHPExcel_Reader_CSV;
use \PHPExcel_Writer_Excel2007;
use \PHPExcel_CachedObjectStorageFactory;

class UploadFilesController extends RestController
{
    /**
     * Method to load a list of uploaded files list.
     *
     * @return mixed
     */
    public function getList()
    {
        // Init
        $cache = $this->di->get('cache');
        $cacheKey = "upload_files";

        // Load record from cache
        $uploadFiles = $cache->get($cacheKey);

        // If no cache is present
        if (!$uploadFiles)
        {
            // Load all upload files
            $results = UploadFile::find();

            // Parse & build results
            $uploadFiles = [];
            foreach ($results as $result)
                $uploadFiles[] = $this->transform($result);

            // Cache results
            $cache->save($cacheKey, $uploadFiles);
        }

        // Finished
        return $this->di->get('json_response')
            ->sendSuccess($uploadFiles);
    }

    /**
     * Method to load single upload file info by id.
     *
     * @param $uploadFileId
     * @return mixed
     * @throws ApiException
     */
    public function getInfo($uploadFileId)
    {
        // Init
        $cache = $this->di->get('cache');
        $cacheKey = "upload_file_$uploadFileId";

        // Load record from cache
        $uploadFile = $cache->get($cacheKey);

        // If no cache is present
        if (!$uploadFile)
        {
            // Load requested upload file
            $uploadFile = UploadFile::findFirst($uploadFileId);
            if (!$uploadFile)
                throw new ApiException('Upload File does not exist.', 404);

            // Transform record
            $uploadFile = $this->transform($uploadFile);

            // Cache result
            $cache->save($cacheKey, $uploadFile);
        }

        // Finished
        return $this->di->get('json_response')
            ->sendSuccess($uploadFile);
    }

    public function createRecord()
    {
        // Load request
        $request = $this->di->get('request');

        // Parse file name
        $originalCsvFilename = $request->getPost('filename');
        if (empty($originalCsvFilename))
            throw new ApiException('You have not specified a file name.', 400);
        if (!preg_match($this->di->get('config')->app->valid_filename_regex, $originalCsvFilename))
            throw new ApiException('File name contains invalid characters. Allowed characters are A-Z a-z 0-9 . - _ and (space).', 400);

        // Parse file data
        $filedata = $request->getPost('filedata');
        if (empty($filedata))
            throw new ApiException('You have not supplied any file data.', 400);

        // Write to a temporary csv file
        $tempCsvFilepath = APP_DIR . 'storage/temp/' . uniqid(md5(time()), true) . '.csv';
        file_put_contents($tempCsvFilepath, $filedata);

        // Process csv file
        $newExcelFilename = pathinfo($originalCsvFilename)['filename'];
        $newExcelFilepath = APP_DIR . 'storage/uploads/' . uniqid($newExcelFilename . '_', true) . '.xlsx';
        $this->convertCsvToExcel($tempCsvFilepath, $newExcelFilepath);

        // Clean up
        if (file_exists($tempCsvFilepath))
            unlink($tempCsvFilepath);

        // Save record in database
        $uploadFile = new UploadFile;
        $uploadFile->original_filename = $originalCsvFilename;
        $uploadFile->new_filename = basename($newExcelFilepath);
        $uploadFile->filesize_bytes = filesize($newExcelFilepath);
        $uploadFile->date_created = date('Y-m-d');

        // Attempt to save the record
        if (!$uploadFile->save())
        {
            // Delete converted excel file
            if (file_exists($newExcelFilepath))
                unlink($newExcelFilepath);

            // Error
            throw new ApiException('Failed to save database record - ' . implode(' | ', $uploadFile->getMessages()), 500);
        }

        // Purge upload_files cache
        $this->di->get('cache')
            ->delete('upload_files');

        // Finished
        $this->di->get('json_response')
            ->sendSuccess($this->transform($uploadFile), 201);
    }

    /**
     * Method to transform UploadFile model data to predictable response structure.
     *
     * @param UploadFile $uploadFile
     * @return array
     */
    private function transform(UploadFile $uploadFile)
    {
        // Finished
        return [
            'id' => $uploadFile->id,
            'original_filename' => $uploadFile->original_filename,
            'new_filename' => $uploadFile->new_filename,
            'filesize' => [
                'bytes' => $uploadFile->filesize_bytes,
                'formatted' => Helper::bytesToReadable($uploadFile->filesize_bytes)
            ],
            'download_link' => Helper::toLink('download/' . $uploadFile->new_filename),
            'date_created' => $uploadFile->date_created
        ];
    }

    /**
     * Method to convert csv to excel file.
     *
     * @param $tempCsvFilepath
     * @param $newExcelFilepath
     */
    private function convertCsvToExcel($tempCsvFilepath, $newExcelFilepath)
    {
        // Load csv parsing config
        $csvConfig = $this->di->get('config')->csv;

        // Load phpexcel
        require_once APP_DIR . '../vendor/phpexcel/phpexcel/Classes/PHPExcel.php';

        // Configure caching rule
        $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
        PHPExcel_Settings::setCacheStorageMethod($cacheMethod);

        // Open csv file
        $csvFile = (new PHPExcel_Reader_CSV())
            ->setDelimiter($csvConfig->delimiter)
            ->setEnclosure($csvConfig->enclosure)
            ->load($tempCsvFilepath)
            ->getActiveSheet();

        // Open excel file
        $excelFile = new PHPExcel();
        $excelFileSheet = $excelFile->getActiveSheet();

        // Convert csv to excel
        foreach ($csvFile->getRowIterator() as $rowIndex => $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            foreach ($cellIterator as $columnIndex => $cell) {
                $excelFileSheet->setCellValueByColumnAndRow($columnIndex, $rowIndex, $cell->getValue());
            }
        }

        // Save excel file
        $excelWriter = new PHPExcel_Writer_Excel2007($excelFile);
        $excelWriter->save($newExcelFilepath);
    }
}
