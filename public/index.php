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

// Register route collection dynamically
$di->set('collections', function () {
    return include(APP_DIR . 'routes/loader.php');
});

// Register singleton instance of the loaded config object
$di->setShared('config', function() use ($config) {
    return $config;
});
$config = null;

// Register singleton instance of session
$di->setShared('session', function() {
    return (new \Phalcon\Session\Adapter\Files())->start();
});

// Register single instance of database adapter
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

// Register cache functionality
$di->set('cache', function() use ($di) {
    $frontendCache = new \Phalcon\Cache\Frontend\Data([
        'lifetime' => $di->get('config')->app->cache_lifetime
    ]);
    $cache = new \Phalcon\Cache\Backend\File($frontendCache, [
        'cacheDir' => APP_DIR . 'storage/cache/'
    ]);
    return $cache;
});

// Create our micro Phalcon application
$app = new Phalcon\Mvc\Micro();
$app->setDI($di);

// Mount all the loaded route collection in the application
foreach ($di->get('collections') as $collection)
    $app->mount($collection);

// Base api end point page
$app->get('/', function() use ($di) {
    include(APP_DIR . 'home.php');
});

// Start handling api requests
$app->handle();