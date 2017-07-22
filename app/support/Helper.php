<?php

namespace TestPhalconApi\Support;

class Helper
{
    /**
     * Helper method to log request.
     */
    public static function logRequest()
    {
        // Check if debug logging is enabled
        if (!self::getDI('config')->app->debug)
            return;

        // Parse request
        $userIp = $_SERVER['REMOTE_ADDR'];
        $executionTime = (microtime(true) - self::getDI('start_time')->getValue());
        $requestUri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : 'N/A';
        $requestMethod = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'N/A';
        $requestBody = file_get_contents('php://input');
        if (!$requestBody || empty($requestBody))
            $requestBody = sizeof($_POST) ? print_r($_POST, true) : null;

        // Log request
        $logger = self::getDI('logger');
        $logger->begin();
        $logger->debug(sprintf(
            "Processed request for %s in %f seconds.\r\n" .
            "Request Uri: %s\r\n" .
            (!empty($requestBody) ? "Request Body: %s\r\n" : ''),
            $userIp,
            $executionTime,
            $requestMethod ." ". $requestUri,
            $requestBody));
        $logger->commit();
    }

    /**
     * Method to log the exception and send it as json response to client.
     *
     * @param \Exception $exception
     * @return mixed
     */
    public static function handleException(\Exception $exception)
    {
        // Check if its an api exception
        $isApiException = is_a($exception, 'TestPhalconApi\\Exceptions\\ApiException');

        // Log unhandled exception as an error
        $logger = self::getDI('logger');
        $logger->begin();
        $logger->error($isApiException ? $exception->getResponseCode() . ' ' . $exception->getErrorMessage() . "\r\n" : $exception->getMessage());
        if (!$isApiException) $logger->debug($exception->getFile() . ':' . $exception->getLine());
        if (!$isApiException) $logger->debug("StackTrace:\r\n" . $exception->getTraceAsString() . "\r\n");
        $logger->commit();

        // Send error response
        return self::getDI('json_response')
            ->sendError(
                $exception->getErrorMessage(),
                $isApiException ? $exception->getResponseCode() : 500
            );
    }

    /**
     * Method to handle file download request by id
     *
     * @param $file_id
     */
    public static function handleDownload($file_id)
    {
        // Log request
        self::logRequest();

        // TODO real implementation
        header("Content-Type: application/vnd.ms-excel");
        readfile(APP_DIR . 'storage/uploads/test.xls');
        exit;
    }

    /**
     * Internal helper method to retrieve default di.
     *
     * @param $type
     * @return mixed
     */
    private function getDI($type)
    {
        return \Phalcon\DI::getDefault()->get($type);
    }
}