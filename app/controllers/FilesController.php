<?php

namespace TestPhalconApi\Controllers;

class FilesController extends RestController
{
    public function getList()
    {
        // TODO
        return $this->di->get('json_response')
            ->sendSuccess([
                ['test' => true]
            ]);
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
}
