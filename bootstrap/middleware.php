<?php

declare(strict_types=1);

use AndrewDyer\Settings\Contracts\SettingsInterface;
use App\Application\Handlers\HttpErrorHandler;
use Psr\Log\LoggerInterface;
use Slim\App;

/*
 * Builds application middleware and default error handling configuration.
 *
 * @param App $app Indicates the Slim application instance to configure.
 * @return void Returns after middleware configuration is complete.
 * @throws \Psr\Container\ContainerExceptionInterface When the container cannot resolve required entries.
 * @internal
 */
return function(App $app): void {
    $app->addBodyParsingMiddleware();

    $app->addRoutingMiddleware();

    $container = $app->getContainer();

    $settings = $container->get(SettingsInterface::class);

    $errorMiddleware = $app->addErrorMiddleware(
        $settings->get('displayErrorDetails'),
        $settings->get('logError'),
        $settings->get('logErrorDetails')
    );

    $logger = $container->has(LoggerInterface::class)
        ? $container->get(LoggerInterface::class)
        : null;

    $errorHandler = new HttpErrorHandler(
        $app->getCallableResolver(),
        $app->getResponseFactory(),
        $logger
    );

    $errorMiddleware->setDefaultErrorHandler($errorHandler);
};
