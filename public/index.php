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
    'TestPhalconApi\Responses' => APP_DIR . '/responses/'
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
$app->get('/', function() use ($app) {
    return (new Phalcon\Http\Response())->redirect('docs/html');
});

// Post-process request request
$app->after(function() use ($di, $start)
{
    // If debug enabled
    if ($di->get('config')->app->debug)
    {
        // Parse request
        $userIp = $_SERVER['REMOTE_ADDR'];
        $executionTime = (microtime(true) - $start);
        $requestUri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : 'N/A';
        $requestMethod = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'N/A';
        $requestBody = file_get_contents('php://input');
        if (!$requestBody || empty($requestBody))
            $requestBody = sizeof($_POST) ? print_r($_POST, true) : null;

        // Log request
        $logger = $di->get('logger');
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
});

// Global 404 error handler
$app->notFound(function() {
    throw new TestPhalconApi\Exceptions\ApiException('File not found.', 404);
});

// Register global exception handler
set_exception_handler(function($exception) use ($di)
{
    // Check if its an api exception
    $isApiException = is_a($exception, 'TestPhalconApi\\Exceptions\\ApiException');

    // Log unhandled exception as an error
    $logger = $di->get('logger');
    $logger->begin();
    $logger->error($isApiException ? $exception->getResponseCode() . ' ' . $exception->getErrorMessage() : $exception->getMessage());
    if (!$isApiException) $logger->debug($exception->getFile() . ':' . $exception->getLine());
    if (!$isApiException) $logger->debug("StackTrace:\r\n" . $exception->getTraceAsString() . "\r\n");
    $logger->commit();

    // Send error response
    return (new TestPhalconApi\Responses\JsonResponse())
        ->sendError(
            $exception->getErrorMessage(),
            $isApiException ? $exception->getResponseCode() : 500
        );
});

// Start handling api requests
$app->handle();
