<?php

namespace TestPhalconApi\Exceptions;

class ApiException extends \Exception
{
    /**
     * Error status code.
     *
     * @var int|null
     */
    private $status_code = null;

    /**
     * ApiException constructor.
     *
     * @param string $message
     * @param int $status_code
     */
    public function __construct($message = 'Unknown Error', $status_code = 500)
    {
        parent::__construct($message);
        $this->status_code = $status_code;
    }

    /**
     * Method to retrieve the error status code.
     *
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->status_code;
    }
}
