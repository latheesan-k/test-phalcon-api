<?php

namespace TestPhalconApi\Exceptions;

class ApiException extends \Exception
{
    /**
     * Class properties.
     *
     * @var string
     */
    public $errorMessage;
    public $responseCode;

    /**
     * ApiException constructor.
     *
     * @param string $errorMessage
     * @param int $responseCode
     */
    public function __construct($errorMessage = "Unknown error occurred", $responseCode = 500)
    {
        $this->errorMessage = $errorMessage;
        $this->responseCode = $responseCode;
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * @return mixed
     */
    public function getResponseCode()
    {
        return $this->responseCode;
    }
}