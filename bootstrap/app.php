<?php

use DI\Container;
use Monolog\Logger;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../vendor/autoload.php';

require_from_root('bootstrap/environment.php')();

$container = new Container();

AppFactory::setContainer($container);

require_from_root('bootstrap/settings.php')($container);

require_from_root('bootstrap/database.php')($container);

require_from_root('bootstrap/services.php')($container);

$app = AppFactory::create();

$app->addRoutingMiddleware();

$app->addBodyParsingMiddleware();

$settings = $container->get('settings')['app'];
$logger = $container->get(Logger::class);
$app->addErrorMiddleware(
    $settings['display_error_details'],
    $settings['log_errors'],
    $settings['log_error_details'],
    $logger
);

require_from_root('bootstrap/middleware.php')($app);

return $app;
