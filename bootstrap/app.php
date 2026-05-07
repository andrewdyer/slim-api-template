<?php

declare(strict_types=1);

use AndrewDyer\JsonErrorHandler\JsonErrorHandler;
use AndrewDyer\Settings\Contracts\SettingsInterface;
use DI\ContainerBuilder;
use Psr\Log\LoggerInterface;
use Slim\App;
use Slim\Factory\AppFactory;

return function(): App {
    $containerBuilder = new ContainerBuilder();

    require_from_root('bootstrap/settings.php')($containerBuilder);
    require_from_root('bootstrap/dependencies.php')($containerBuilder);
    require_from_root('bootstrap/repositories.php')($containerBuilder);

    $container = $containerBuilder->build();

    AppFactory::setContainer($container);

    $app = AppFactory::create();

    $app->addBodyParsingMiddleware();
    $app->addRoutingMiddleware();

    require_from_root('bootstrap/middleware.php')($app);

    $settings = $container->get(SettingsInterface::class);

    $errorMiddleware = $app->addErrorMiddleware(
        displayErrorDetails: $settings->get('displayErrorDetails'),
        logErrors: $settings->get('logError'),
        logErrorDetails: $settings->get('logErrorDetails')
    );

    $errorHandler = new JsonErrorHandler(
        callableResolver: $app->getCallableResolver(),
        responseFactory: $app->getResponseFactory(),
        logger: $container->has(LoggerInterface::class)
            ? $container->get(LoggerInterface::class)
            : null
    );

    $errorMiddleware->setDefaultErrorHandler($errorHandler);

    require_from_root('bootstrap/routes.php')($app);

    return $app;
};
