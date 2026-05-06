<?php

declare(strict_types=1);

use AndrewDyer\JsonErrorHandler\JsonErrorHandler;
use AndrewDyer\Settings\Contracts\SettingsInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Slim\App;
use Slim\Factory\AppFactory;

return function (ContainerInterface $container): App {
    AppFactory::setContainer($container);

    $app = AppFactory::create();

    $logger = $container->has(LoggerInterface::class)
        ? $container->get(LoggerInterface::class)
        : null;

    $errorHandler = new JsonErrorHandler(
        $app->getCallableResolver(),
        $app->getResponseFactory(),
        $logger
    );

    $app->addBodyParsingMiddleware();
    $app->addRoutingMiddleware();

    require_from_root('bootstrap/middleware.php')($app);

    $settings = $container->get(SettingsInterface::class);

    $errorMiddleware = $app->addErrorMiddleware(
        $settings->get('displayErrorDetails'),
        $settings->get('logError'),
        $settings->get('logErrorDetails')
    );

    $errorMiddleware->setDefaultErrorHandler($errorHandler);

    require_from_root('bootstrap/routes.php')($app);

    return $app;
};