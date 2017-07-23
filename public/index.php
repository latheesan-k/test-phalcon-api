<?php

use Phalcon\Http\Response;
use TestPhalconApi\Support\Helper;
use Phalcon\Mvc\Micro as MicroApplication;
use TestPhalconApi\Exceptions\ApiException;

// Initialize the application
include getcwd() . '/../app/bootstrap.php';

// Create our micro Phalcon application
$app = new MicroApplication();
$app->setDI($di);

// Mount all the loaded route collection in the application
foreach ($di->get('collections') as $collection)
    $app->mount($collection);

// Show API documentation page by default
$app->get('/', function() {
    return (new Response())->redirect('docs/html');
});

// Handle file download request
$app->get('/download/{filename}', function($filename) {
    return Helper::handleDownload($filename);
});

// Post-process request request
$app->after(function() {
    Helper::logRequest();
});

// Register global 404 error handler
$app->notFound(function() {
    $requestUri = (isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : 'N/A');
    throw new ApiException(
        'File not found. ' . $requestUri, 404);
});

// Register global exception handler
set_exception_handler(function($exception) {
    return Helper::handleException($exception);
});

// Start handling api requests
$app->handle();
