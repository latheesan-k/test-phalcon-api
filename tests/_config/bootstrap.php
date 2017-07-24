<?php

use Phalcon\Mvc\Micro as MicroApplication;

// Bootstrap our application
require TESTS_PATH . '../app/bootstrap.php';

// Create our micro Phalcon application
$app = new MicroApplication();
$app->setDI($di);
return $app;