<?php

namespace TestPhalconApi\Test\Unit\UploadFile;

use TestPhalconApi\Models\UploadFile;

class UploadFileTest extends \Codeception\TestCase\Test
{
    /**
     * The UploadFile model.
     *
     * @var UploadFile
     */
    protected $uploadFile;

    /**
     * Model constructor.
     */
    protected function _before()
    {
        $this->uploadFile = new UploadFile;
    }

    /**
     * Test if model's source table name matches.
     */
    public function testGetSource()
    {
        $this->assertEquals($this->uploadFile->getSource(), 'upload_files');
    }
}
