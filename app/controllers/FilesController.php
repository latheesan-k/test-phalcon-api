<?php

namespace TestPhalconApi\Controllers;

use \TestPhalconApi\Models\UploadFile;
use \TestPhalconApi\Support\Helper;

class FilesController extends RestController
{
    /**
     * Method to load a list of uploaded files list
     *
     * @return mixed
     */
    public function getList()
    {
        // Load all upload files
        $uploadFiles = UploadFile::find();

        // Parse & build results
        $results = [];
        foreach ($uploadFiles as $uploadFile)
            $results[] = $this->transform($uploadFile);

        // Finished
        return $this->di->get('json_response')
            ->sendSuccess($results);
    }

    public function getInfo()
    {
        // TODO
        echo 'file info';
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
