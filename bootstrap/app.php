<?php

use Monolog\Logger;
use Slim\Factory\AppFactory;

// Bootstrap application
require_once __DIR__ . '/../vendor/autoload.php';
require_from_root('bootstrap/environment.php')();

// Setup dependency injection
$container = new DI\Container();
AppFactory::setContainer($container);
require_from_root('bootstrap/settings.php')($container);
require_from_root('bootstrap/container.php')($container);

// Create Slim application
$app = AppFactory::create();

// Add core middleware (executes in reverse order: Error → Body → Routing)
$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();

// Error handling (needs container for settings)
$settings = $container->get('settings');
$logger = $container->get(Logger::class);
$app->addErrorMiddleware(
    $settings['app']['display_error_details'],
    $settings['app']['log_errors'],
    $settings['app']['log_error_details'],
    $logger
);

// Load application-specific middleware (Twig, CORS, Auth, etc.)
require_from_root('bootstrap/middleware.php')($app);

// Register application routes
require_from_root('bootstrap/routes.php')($app);

// Return configured application instance
return $app;
