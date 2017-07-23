<?php

namespace TestPhalconApi\Support;

use TestPhalconApi\Models\UploadFile;
use TestPhalconApi\Exceptions\ApiException;

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
        $logger = self::getDI('debug_logger');
        $logger->begin();
        $logger->debug(sprintf(
            "Processed request for %s in %f seconds.\r\n" .
            "Request Uri: %s\r\n" .
            (!empty($requestBody) ? "Request Body: %s\r\n" : ''),
            $userIp,
            $executionTime,
            $requestMethod ." ". $requestUri,
            trim($requestBody)));
        $logger->commit();
    }

    /**
     * Method to log the exception and send it as json response to client.
     *
     * @param $exception
     * @return mixed
     */
    public static function handleException($exception)
    {
        // Log unhandled exception as an error
        $logger = self::getDI('error_logger');
        $logger->begin();
        $logger->error($exception->getMessage());
        $logger->debug($exception->getFile() . ':' . $exception->getLine());
        $logger->debug("StackTrace:\r\n" . $exception->getTraceAsString() . "\r\n");
        $logger->commit();

        // Parse status code
        $statusCode = 500;
        if (is_a($exception, 'TestPhalconApi\\Exceptions\\ApiException')) {
            $statusCode = $exception->getStatusCode();
        }

        // Parse error message
        $errorMessage = $exception->getMessage();
        if (self::getDI('config')->app->debug) {
            $errorMessage .= ' @ ' . basename($exception->getFile()) . ':' . $exception->getLine();
        }

        // Send error response
        return self::getDI('json_response')
            ->sendError($errorMessage, $statusCode);
    }

    /**
     * Simple helper method to format bytes into human redable format
     * @credits http://jeffreysambells.com/2012/10/25/human-readable-filesize-php
     *
     * @param $bytes
     * @param int $decimals
     * @return string
     */
    public static function bytesToReadable($bytes, $decimals = 2) {
        $size = array('B','KB','MB','GB','TB','PB','EB','ZB','YB');
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
    }

    /**
     * Method to generate web link relative to the application uri.
     *
     * @param $path
     * @return string
     */
    public static function toLink($path)
    {
        // Parse server protocol
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';

        // Finished
        return $protocol . $_SERVER['HTTP_HOST'] . '/' . $path;
    }

    /**
     * Method to handle file download request by id
     *
     * @param $filename
     * @throws ApiException
     */
    public static function handleDownload($filename)
    {
        // Validate file name
        if (!preg_match(self::getDI('config')->app->valid_filename_regex, $filename))
            throw new ApiException('Invalid file name: ' . $filename);

        // Log request
        self::logRequest();

        // Load requested upload file
        $uploadFile = UploadFile::findFirst(
            [
                'new_filename = :filename:',
                'bind' => [
                    'filename' => $filename
                ]
            ]
        );
        if (!$uploadFile)
            throw new ApiException('Upload File does not exist.', 404);

        // Generate local file path
        $localFilepath = APP_DIR . 'storage/uploads/'. $uploadFile->new_filename;

        // Check if local file exist
        if (!file_exists($localFilepath))
            throw new ApiException('Requested file does not exist on the server. It might be deleted.');

        // Send file to user
        header('Content-Disposition: attachment; filename="' . $uploadFile->new_filename . '"');
        header('Content-Length: ' . $uploadFile->filesize_bytes);
        header('Content-Type: application/vnd.ms-excel');
        readfile($localFilepath);
        exit;
    }

    /**
     * Internal helper method to retrieve default di.
     *
     * @param $type
     * @return mixed
     */
    private static function getDI($type)
    {
        return \Phalcon\DI::getDefault()->get($type);
    }
}