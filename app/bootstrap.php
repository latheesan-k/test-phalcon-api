<?php

// Load required namespaces
use Phalcon\Config\Adapter\Ini;

// Define application directory globally
define('APP_DIR', getcwd() . '/../app/');

// Attempt to load application configuration
$configFile = APP_DIR . 'config/settings.ini';
if (!file_exists($configFile))
    throw new Exception("Config file $configFile does not exists");
$config = new Ini($configFile);

// Configure application defaults
date_default_timezone_set($config->app->timezone);
