<?php

namespace TestPhalconApi\Controllers;

class RestController extends \Phalcon\DI\Injectable
{
    /**
     * RestController constructor.
     */
    public function __construct()
    {
        // Load default di
        $this->setDI(\Phalcon\DI::getDefault());
    }

    /**
     * Method to define the base endpoint access controls
     *
     * @return bool
     */
    public function baseEndpoint()
    {
        // Configure access controls
        return $this->configureAccessControls('GET, POST');
    }

    /**
     * Method to define the record endpoint access controls
     *
     * @return bool
     */
    public function recordEndpoint()
    {
        // Configure access controls
        return $this->configureAccessControls('GET');
    }

    /**
     * Sets the access control on the response header for specified methods
     *
     * @param $allowedMethods
     * @return bool
     */
    private function configureAccessControls($allowedMethods)
    {
        // Configure access controls
        $response = $this->di->get('response');
        $response->setHeader('Access-Control-Allow-Methods', $allowedMethods);
        $response->setHeader('Access-Control-Allow-Origin', $this->di->get('request')->header('Origin'));
        $response->setHeader('Access-Control-Allow-Credentials', 'true');
        $response->setHeader('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type');
        $response->setHeader('Access-Control-Max-Age', 86400);
        return true;
    }
}
