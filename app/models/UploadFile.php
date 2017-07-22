<?php

namespace TestPhalconApi\Models;

use Phalcon\Mvc\Model;

class UploadFile extends Model
{
    /**
     * Configure public properties
     */
    public $id;
    public $original_filename;
    public $new_filename;
    public $filesize_bytes;
    public $date_created;

    /**
     * Set mysql table name where this model data is stored
     */
    public function initialize()
    {
        $this->setSource('upload_files');
    }
}