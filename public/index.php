<?php

declare(strict_types=1);

use AndrewDyer\CorsResponseEmitter\CorsResponseEmitter;
use Slim\Factory\ServerRequestCreatorFactory;

require __DIR__ . '/../vendor/autoload.php';

$appFactory = require __DIR__ . '/../bootstrap/app.php';

$app = $appFactory();

$serverRequestCreator = ServerRequestCreatorFactory::create();
$request = $serverRequestCreator->createServerRequestFromGlobals();

$container = $app->getContainer();

$response = $app->handle($request);
$corsResponseEmitter = $container->get(CorsResponseEmitter::class);
$corsResponseEmitter->emit($response);
