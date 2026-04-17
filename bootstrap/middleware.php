<?php

declare(strict_types=1);

use AndrewDyer\Settings\Contracts\SettingsInterface;
use Slim\App;
use Slim\Interfaces\ErrorHandlerInterface;

/*
 * Builds application middleware and default error handling configuration.
 *
 * @param App $app Indicates the Slim application instance to configure.
 * @return void Returns after middleware configuration is complete.
 * @throws \Psr\Container\ContainerExceptionInterface When the container cannot resolve required entries.
 * @internal
 */
return function(App $app, ErrorHandlerInterface $errorHandler): void {
    $app->addBodyParsingMiddleware();

    $app->addRoutingMiddleware();

    $container = $app->getContainer();

    $settings = $container->get(SettingsInterface::class);

    $errorMiddleware = $app->addErrorMiddleware(
        $settings->get('displayErrorDetails'),
        $settings->get('logError'),
        $settings->get('logErrorDetails')
    );

    $errorMiddleware->setDefaultErrorHandler($errorHandler);
};
