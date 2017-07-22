<?php

namespace TestPhalconApi\Controllers;

class BaseController extends \Phalcon\DI\Injectable
{
    /**
     * BaseController constructor.
     */
    public function __construct()
    {
        // Load default di
        $this->setDI(\Phalcon\DI::getDefault());
    }
}
