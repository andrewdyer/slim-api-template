<?php

declare(strict_types=1);

use AndrewDyer\JsonErrorHandler\JsonErrorHandler;
use DI\ContainerBuilder;
use Psr\Log\LoggerInterface;
use Slim\App;
use Slim\Factory\AppFactory;

return function(): App {
    // Load environment
    require_from_root('bootstrap/environment.php')('.env');

    // Build container
    $containerBuilder = new ContainerBuilder();
    require_from_root('bootstrap/settings.php')($containerBuilder);
    require_from_root('bootstrap/dependencies.php')($containerBuilder);
    require_from_root('bootstrap/repositories.php')($containerBuilder);

    $container = $containerBuilder->build();

    // Create app
    AppFactory::setContainer($container);
    $app = AppFactory::create();

    // Register app-dependent services
    $logger = $container->has(LoggerInterface::class)
        ? $container->get(LoggerInterface::class)
        : null;

    $container->set(
        JsonErrorHandler::class,
        new JsonErrorHandler($app->getCallableResolver(), $app->getResponseFactory(), $logger)
    );

    // Middleware
    require_from_root('bootstrap/middleware.php')($app);

    // Routes
    require_from_root('bootstrap/routes.php')($app);

    return $app;
};
