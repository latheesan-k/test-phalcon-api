<?php

// Initialize the application
include getcwd() . '/../app/bootstrap.php';

// Create our micro Phalcon application
$app = new Phalcon\Mvc\Micro();
$app->setDI($di);

// Mount all the loaded route collection in the application
foreach ($di->get('collections') as $collection)
    $app->mount($collection);

// Show API documentation page by default
$app->get('/', function() {
    return (new Phalcon\Http\Response())->redirect('docs/html');
});

// Handle file download request
$app->get('/download/{filename}', function($filename) {
    return TestPhalconApi\Support\Helper::handleDownload($filename);
});

// Post-process request request
$app->after(function() {
    TestPhalconApi\Support\Helper::logRequest();
});

// Register global 404 error handler
$app->notFound(function() {
    $requestUri = (isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : 'N/A');
    throw new TestPhalconApi\Exceptions\ApiException(
        'File not found. ' . $requestUri, 404);
});

// Register global exception handler
set_exception_handler(function($exception) {
    return TestPhalconApi\Support\Helper::handleException($exception);
});

// Start handling api requests
$app->handle();
