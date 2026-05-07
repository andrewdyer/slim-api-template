<?php

declare(strict_types=1);

use AndrewDyer\CorsResponseEmitter\CorsResponseEmitter;
use AndrewDyer\JsonErrorHandler\JsonErrorHandler;
use AndrewDyer\Settings\Contracts\SettingsInterface;
use AndrewDyer\ShutdownHandler\Adapters\CallableErrorResponder;
use AndrewDyer\ShutdownHandler\Adapters\CallableResponseEmitter;
use AndrewDyer\ShutdownHandler\ShutdownHandler;
use Psr\Log\LoggerInterface;
use Slim\Factory\ServerRequestCreatorFactory;

require __DIR__ . '/../vendor/autoload.php';

// Load environment configuration
require_from_root('runtime/environment.php')();

// Create configured Slim application
$app = require_from_root('bootstrap/app.php')();

// Get dependency injection container
$container = $app->getContainer();

// Create PSR-7 request from PHP globals
$request = ServerRequestCreatorFactory::create()
    ->createServerRequestFromGlobals();

// Resolve application settings
$settings = $container->get(SettingsInterface::class);

$displayErrorDetails = $settings->get('displayErrorDetails');
$logError = $settings->get('logError');
$logErrorDetails = $settings->get('logErrorDetails');

// Create response emitter (adds CORS headers)
$responseEmitter = new CorsResponseEmitter(
    allowedOrigins: $settings->get('cors.allowedOrigins')
);

// Create JSON error handler
$errorHandler = new JsonErrorHandler(
    callableResolver: $app->getCallableResolver(),
    responseFactory: $app->getResponseFactory(),
    logger: $container->has(LoggerInterface::class)
        ? $container->get(LoggerInterface::class)
        : null
);

// Register shutdown handler for fatal errors
$shutdownHandler = new ShutdownHandler(
    request: $request,
    errorResponder: new CallableErrorResponder(
        static function(
            $request,
            $exception,
            bool $displayErrorDetails
        ) use (
            $errorHandler,
            $logError,
            $logErrorDetails
        ) {
            return $errorHandler(
                request: $request,
                exception: $exception,
                displayErrorDetails: $displayErrorDetails,
                logErrors: $logError,
                logErrorDetails: $logErrorDetails
            );
        }
    ),
    responseEmitter: new CallableResponseEmitter(
        static function($response) use ($responseEmitter): void {
            $responseEmitter->emit($response);
        }
    ),
    displayErrorDetails: $displayErrorDetails
);

register_shutdown_function($shutdownHandler);

// Handle HTTP request
$response = $app->handle($request);

// Emit HTTP response
$responseEmitter->emit($response);
