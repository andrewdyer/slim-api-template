<?php

declare(strict_types=1);

use DI\ContainerBuilder;
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

    // Middleware
    require_from_root('bootstrap/middleware.php')($app);

    // Routes
    require_from_root('bootstrap/routes.php')($app);

    return $app;
};
