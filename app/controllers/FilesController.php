<?php

namespace TestPhalconApi\Controllers;

use TestPhalconApi\Support\Helper;
use TestPhalconApi\Models\UploadFile;
use TestPhalconApi\Exceptions\ApiException;

class FilesController extends RestController
{
    /**
     * Method to load a list of uploaded files list
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
     * @param $upload_file_id
     * @return mixed
     * @throws ApiException
     */
    public function getInfo($upload_file_id)
    {
        // Init
        $cache = $this->di->get('cache');
        $cacheKey = "upload_file_$upload_file_id";

        // Load record from cache
        $uploadFile = $cache->get($cacheKey);

        // If no cache is present
        if (!$uploadFile)
        {
            // Load requested upload file
            $uploadFile = UploadFile::findFirst($upload_file_id);
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
        // TODO
        echo 'create result';
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
            'download_link' => Helper::toLink('download/' . $uploadFile->id),
            'date_created' => $uploadFile->date_created
        ];
    }
}
