<?php

declare(strict_types=1);

use AndrewDyer\CorsResponseEmitter\CorsResponseEmitter;
use Slim\Factory\ServerRequestCreatorFactory;

require __DIR__ . '/../vendor/autoload.php';

$appFactory = require __DIR__ . '/../bootstrap/app.php';
$app = $appFactory();

// Create request from globals **once**
$serverRequestCreator = ServerRequestCreatorFactory::create();
$request = $serverRequestCreator->createServerRequestFromGlobals();

// Register shutdown/error handling with the **same request**
require_from_root('bootstrap/errors.php')($app, $request);

// Handle request
$container = $app->getContainer();
$response = $app->handle($request);

// Emit CORS headers + response
$corsResponseEmitter = $container->get(CorsResponseEmitter::class);
$corsResponseEmitter->emit($response);
