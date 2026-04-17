<?php

declare(strict_types=1);

use AndrewDyer\CorsResponseEmitter\CorsResponseEmitter;
use AndrewDyer\Settings\Contracts\SettingsInterface;
use AndrewDyer\ShutdownHandler\Adapters\CallableErrorResponder;
use AndrewDyer\ShutdownHandler\Adapters\CallableResponseEmitter;
use AndrewDyer\ShutdownHandler\ShutdownHandler;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Interfaces\ErrorHandlerInterface;

/*
 * Builds application error handling, including shutdown error handling.
 *
 * @param App $app Indicates the Slim application instance.
 * @param ServerRequestInterface $request Indicates the server request instance.
 * @return void Returns after error handling configuration is complete.
 * @throws ContainerExceptionInterface When the container cannot resolve required entries.
 * @internal
 */
return function(App $app, ServerRequestInterface $request, ErrorHandlerInterface $errorHandler): void {
    $container = $app->getContainer();

    $settings = $container->get(SettingsInterface::class);

    $displayErrorDetails = $settings->get('displayErrorDetails');
    $logError = $settings->get('logError');
    $logErrorDetails = $settings->get('logErrorDetails');

    $corsResponseEmitter = $container->get(CorsResponseEmitter::class);

    $shutdownErrorResponder = new CallableErrorResponder(
        static fn ($request, $exception, bool $displayErrorDetails) => $errorHandler(
            $request,
            $exception,
            $displayErrorDetails,
            $logError,
            $logErrorDetails
        )
    );

    $shutdownResponseEmitter = new CallableResponseEmitter(
        static function($response) use ($corsResponseEmitter): void {
            $corsResponseEmitter->emit($response);
        }
    );

    $shutdownHandler = new ShutdownHandler(
        $request,
        $shutdownErrorResponder,
        $shutdownResponseEmitter,
        $displayErrorDetails
    );

    register_shutdown_function($shutdownHandler);
};
