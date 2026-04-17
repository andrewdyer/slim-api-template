<?php

declare(strict_types=1);

use AndrewDyer\CorsResponseEmitter\CorsResponseEmitter;
use Slim\Factory\ServerRequestCreatorFactory;

require __DIR__ . '/../vendor/autoload.php';

$serverRequestCreator = ServerRequestCreatorFactory::create();
$request = $serverRequestCreator->createServerRequestFromGlobals();

$appFactory = require __DIR__ . '/../bootstrap/app.php';
$app = $appFactory($request);

$response = $app->handle($request);

$app->getContainer()->get(CorsResponseEmitter::class)->emit($response);
