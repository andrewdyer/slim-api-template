<?php

use Monolog\Logger;
use Slim\Factory\AppFactory;

// Load Composer autoloader and initialize environment variables
// This establishes the foundation by loading all dependencies and configuration
require_once __DIR__ . '/../vendor/autoload.php';
require_from_root('bootstrap/environment.php')();

// Configure dependency injection container with all application services
// Sets up the DI container and registers core services, and repositories
$container = new DI\Container();
AppFactory::setContainer($container);
require_from_root('bootstrap/settings.php')($container);
require_from_root('bootstrap/container.php')($container);
require_from_root('bootstrap/repositories.php')($container);

// Initialize the Slim application instance with the configured container
$app = AppFactory::create();

// Register core middleware stack in reverse execution order
// Routing middleware enables route matching, body parsing handles request data
$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();

// Configure error handling middleware with application settings and logging
// Retrieves display and logging preferences from settings to handle exceptions appropriately
$settings = $container->get('settings');
$logger = $container->get(Logger::class);
$app->addErrorMiddleware(
    $settings['app']['display_error_details'],
    $settings['app']['log_errors'],
    $settings['app']['log_error_details'],
    $logger
);

// Load and register all application routes from the routes configuration
require_from_root('bootstrap/routes.php')($app);

// Return configured application instance
return $app;
