<?php

require '../Include/Config.php';
//require '../Include/Functions.php';

// This file is generated by Composer
require_once dirname(__FILE__) . '/../vendor/autoload.php';

// Instantiate the app
//$settings = require __DIR__ . '/settings.php';
$app = new \Slim\App();
$container = $app->getContainer();

// Set up
require __DIR__.'/dependencies.php';
require __DIR__ . '/../Include/slim/error-handler.php';
$settings = require __DIR__ . '/../Include/slim/settings.php';

// routes
require __DIR__ . '/routes/register.php';
require __DIR__ . '/routes/password.php';
require __DIR__ . '/routes/verify.php';

// Run app
$app->run();
