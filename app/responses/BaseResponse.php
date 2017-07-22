<?php

namespace TestPhalconApi\Responses;

class BaseResponse extends \Phalcon\DI\Injectable
{
    /**
     * BaseResponse constructor.
     */
    public function __construct()
    {
        // Load default di
        $this->setDI(\Phalcon\DI::getDefault());
    }
}
