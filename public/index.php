<?php

declare(strict_types=1);

use AndrewDyer\CorsResponseEmitter\CorsResponseEmitter;
use AndrewDyer\Settings\Contracts\SettingsInterface;
use Slim\Factory\ServerRequestCreatorFactory;

require __DIR__ . '/../vendor/autoload.php';

$appFactory = require __DIR__ . '/../bootstrap/app.php';

$app = $appFactory();

$serverRequestCreator = ServerRequestCreatorFactory::create();
$request = $serverRequestCreator->createServerRequestFromGlobals();

$container = $app->getContainer();

$settings = $container->get(SettingsInterface::class);

$response = $app->handle($request);
$corsResponseEmitter = new CorsResponseEmitter($settings->get('cors.allowedOrigins'));
$corsResponseEmitter->emit($response);
