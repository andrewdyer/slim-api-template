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

require_from_root('bootstrap/controllers.php')($container);

require_from_root('bootstrap/services.php')($container);

$app = AppFactory::create();

// Enable route parsing and matching
$app->addRoutingMiddleware();

// Automatically parse JSON, form, and multipart bodies into $request->getParsedBody()
$app->addBodyParsingMiddleware();

// Register error handling middleware with settings and logger
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
