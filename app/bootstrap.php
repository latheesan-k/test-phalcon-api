<?php

// Load required namespaces
use Phalcon\Loader;
use Phalcon\DI\FactoryDefault;
use Phalcon\Config\Adapter\Ini;

// Record application start time
$start = microtime(true);

// Define application directory globally
define('APP_DIR', getcwd() . '/../app/');

// Attempt to load application configuration
$configFile = APP_DIR . 'config/settings.ini';
if (!file_exists($configFile))
    throw new Exception("Config file $configFile does not exists");
$config = new Ini($configFile);

// Configure application defaults
date_default_timezone_set($config->app->timezone);

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

// Register application start time
$di->setShared('start_time', function() use ($start) {
    return new TestPhalconApi\Support\StartTime($start);
});
$start = null;

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
        'prefix'  => 'tpa-',
        'frontend' => $frontendCache,
        'adapter' => 'file'
    ]);
});
