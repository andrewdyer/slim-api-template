<?php

declare(strict_types=1);

use AndrewDyer\JsonErrorHandler\JsonErrorHandler;
use AndrewDyer\Settings\Contracts\SettingsInterface;
use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager as Capsule;
use Psr\Log\LoggerInterface;
use Slim\App;
use Slim\Factory\AppFactory;

/**
 * Creates and configures the Slim application instance.
 */
return function(): App {
    // Load environment variables from .env file
    if (!get_env('APP_ENV')) {
        Dotenv::createImmutable(root_path('/'))->load();
    }

    // Initialize dependency injection container builder
    $containerBuilder = new ContainerBuilder();

    // Register application settings
    require_from_root('bootstrap/settings.php')($containerBuilder);

    // Register service dependencies
    require_from_root('bootstrap/dependencies.php')($containerBuilder);

    // Register repository implementations
    require_from_root('bootstrap/repositories.php')($containerBuilder);

    // Build the container
    $container = $containerBuilder->build();

    // Configure Slim to use the container
    AppFactory::setContainer($container);

    // Create Slim application instance
    $app = AppFactory::create();

    // Add body parsing middleware
    $app->addBodyParsingMiddleware();

    // Add routing middleware
    $app->addRoutingMiddleware();

    // Register custom middleware
    require_from_root('bootstrap/middleware.php')($app);

    // Resolve application settings
    $settings = $container->get(SettingsInterface::class);

    // Add error handling middleware
    $errorMiddleware = $app->addErrorMiddleware(
        displayErrorDetails: $settings->get('displayErrorDetails'),
        logErrors: $settings->get('logError'),
        logErrorDetails: $settings->get('logErrorDetails')
    );

    // Create JSON error handler
    $errorHandler = new JsonErrorHandler(
        callableResolver: $app->getCallableResolver(),
        responseFactory: $app->getResponseFactory(),
        logger: $container->has(LoggerInterface::class)
        ? $container->get(LoggerInterface::class)
        : null
    );

    // Set default error handler
    $errorMiddleware->setDefaultErrorHandler($errorHandler);

    // Boot Eloquent ORM
    $capsule = $container->get(Capsule::class);
    $capsule->bootEloquent();

    // Register application routes
    require_from_root('bootstrap/routes.php')($app);

    return $app;
};
