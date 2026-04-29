<?php

declare(strict_types=1);

use AndrewDyer\CorsResponseEmitter\CorsResponseEmitter;
use AndrewDyer\JsonErrorHandler\JsonErrorHandler;
use AndrewDyer\Settings\Contracts\SettingsInterface;
use AndrewDyer\ShutdownHandler\Adapters\CallableErrorResponder;
use AndrewDyer\ShutdownHandler\Adapters\CallableResponseEmitter;
use AndrewDyer\ShutdownHandler\ShutdownHandler;
use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Psr\Log\LoggerInterface;
use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;

require __DIR__ . '/../vendor/autoload.php';

// Load environment variables (if not already loaded)
if (!get_env('APP_ENV')) {
    Dotenv::createImmutable(root_path('/'))->load();
}

// Build dependency injection container
$containerBuilder = new ContainerBuilder();

require_from_root('bootstrap/settings.php')($containerBuilder);
require_from_root('bootstrap/dependencies.php')($containerBuilder);
require_from_root('bootstrap/repositories.php')($containerBuilder);

$container = $containerBuilder->build();

// Create Slim application
AppFactory::setContainer($container);
$app = AppFactory::create();

// Create server request (PSR-7 from globals)
$request = ServerRequestCreatorFactory::create()
    ->createServerRequestFromGlobals();

// Resolve application services and config
$settings = $container->get(SettingsInterface::class);

$logger = $container->has(LoggerInterface::class)
    ? $container->get(LoggerInterface::class)
    : null;

$displayErrorDetails = $settings->get('displayErrorDetails');
$logError = $settings->get('logError');
$logErrorDetails = $settings->get('logErrorDetails');

// Create error handler (JSON responses)
$errorHandler = new JsonErrorHandler(
    $app->getCallableResolver(),
    $app->getResponseFactory(),
    $logger
);

// Create response emitter (adds CORS headers)
$responseEmitter = new CorsResponseEmitter(
    $settings->get('cors.allowedOrigins')
);

// Register shutdown handler for fatal errors
$shutdownHandler = new ShutdownHandler(
    $request,
    new CallableErrorResponder(
        static function ($request, $exception, bool $display) use ($errorHandler, $logError, $logErrorDetails) {
            return $errorHandler(
                $request,
                $exception,
                $display,
                $logError,
                $logErrorDetails
            );
        }
    ),
    new CallableResponseEmitter(
        static function ($response) use ($responseEmitter): void {
            $responseEmitter->emit($response);
        }
    ),
    $displayErrorDetails
);

register_shutdown_function($shutdownHandler);

// Register middleware
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();

require_from_root('bootstrap/middleware.php')($app);

// Register error middleware
$errorMiddleware = $app->addErrorMiddleware(
    $displayErrorDetails,
    $logError,
    $logErrorDetails
);

$errorMiddleware->setDefaultErrorHandler($errorHandler);

// Register routes
require_from_root('bootstrap/routes.php')($app);

// Handle request and emit response
$response = $app->handle($request);
$responseEmitter->emit($response);