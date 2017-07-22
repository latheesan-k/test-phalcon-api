<?php

// Load required namespaces
use Phalcon\DI\FactoryDefault;
use Phalcon\Loader;

// Initialize the application
include getcwd() . '/../app/bootstrap.php';

// Register custom namespaces
(new Loader())->registerNamespaces([
    'TestPhalconApi\Models' => APP_DIR . '/models/',
    'TestPhalconApi\Controllers' => APP_DIR . '/controllers/',
    'TestPhalconApi\Exceptions' => APP_DIR . '/exceptions/',
    'TestPhalconApi\Responses' => APP_DIR . '/responses/',
    'TestPhalconApi\Support' => APP_DIR . '/support/'
])->register();

// Create a new di to share resources across the application
$di = new FactoryDefault();

// Register route collections dynamically
$di->set('collections', function () {
    return include(APP_DIR . 'routes/loader.php');
});

// Register singleton instance of the loaded config object
$di->setShared('config', function() use ($config) {
    return $config;
});
$config = null;

// Register singleton instance of session manager
$di->setShared('session', function() use ($di) {
    return Factory::load([
        'lifetime'   => $di->get('config')->app->session_lifetime,
        'prefix'     => 'tpa_',
        'adapter'    => 'file'
    ]);
});

// Register single instance of database connection
$di->setShared('db', function() use ($di) {
    $dbConfig = $di->get('config')->database;
    return Phalcon\Db\Adapter\Pdo\Factory::load([
        'adapter'  => $dbConfig->adapter,
        'host' => $dbConfig->host,
        'port' => $dbConfig->port,
        'username' => $dbConfig->username,
        'password' => $dbConfig->password,
        'dbname' => $dbConfig->dbname
    ]);
});

// Register singleton instance of the logger
$di->setShared('logger', function() {
    return Phalcon\Logger\Factory::load([
        'name' => APP_DIR . 'storage/logs/' . date('d-m-Y') . '_logs.txt',
        'adapter' => 'file'
    ]);
});

// Register singleton instance of the json responder
$di->setShared('json_response', function() {
    return new TestPhalconApi\Responses\JsonResponse();
});

// Register singleton instance of the caching functionality
$di->setShared('cache', function() use ($di) {
    $frontendCache = Phalcon\Cache\Frontend\Factory::load([
        'lifetime' => $di->get('config')->app->cache_lifetime,
        'adapter'  => 'data'
    ]);
    return Phalcon\Cache\Backend\Factory::load([
        'cacheDir' => APP_DIR . 'storage/cache/',
        'prefix'  => 'app-data',
        'frontend' => $frontendCache,
        'adapter' => 'file'
    ]);
});

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
$app->get('/download/{file_id:[0-9]+}', function($file_id) {
    return TestPhalconApi\Support\Helper::handleDownload($file_id);
});

// Post-process request request
$app->after(function() use ($app, $start) {
    TestPhalconApi\Support\Helper::logRequest($start);
});

// Register global 404 error handler
$app->notFound(function() {
    throw new TestPhalconApi\Exceptions\ApiException(
        'File not found. ' . (isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : 'N/A'), 404);
});

// Register global exception handler
set_exception_handler(function($exception) {
    return TestPhalconApi\Support\Helper::handleException($exception);
});

// Start handling api requests
$app->handle();
