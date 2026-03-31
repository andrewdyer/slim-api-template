<?php

declare(strict_types=1);

use AndrewDyer\Slim\CorsResponseEmitter;
use Slim\Factory\ServerRequestCreatorFactory;

require __DIR__ . '/../vendor/autoload.php';

$appFactory = require __DIR__ . '/../bootstrap/app.php';

$app = $appFactory();

$serverRequestCreator = ServerRequestCreatorFactory::create();
$request = $serverRequestCreator->createServerRequestFromGlobals();

$response = $app->handle($request);
$corsResponseEmitter = new CorsResponseEmitter();
$corsResponseEmitter->emit($response);
