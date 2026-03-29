<?php

use AndrewDyer\Settings\Contracts\SettingsInterface;
use DI\ContainerBuilder;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../vendor/autoload.php';

require_from_root('bootstrap/environment.php')('.env');

$containerBuilder = new ContainerBuilder();

require_from_root('bootstrap/settings.php')($containerBuilder);
require_from_root('bootstrap/dependencies.php')($containerBuilder);

$container = $containerBuilder->build();

AppFactory::setContainer($container);

$app = AppFactory::create();

$app->addRoutingMiddleware();

$settings = $container->get(SettingsInterface::class);

$errorMiddleware = $app->addErrorMiddleware(
    $settings->get('displayErrorDetails'),
    $settings->get('logError'),
    $settings->get('logErrorDetails')
);

require_from_root('bootstrap/routes.php')($app);
