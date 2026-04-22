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
use Dotenv\Exception\InvalidPathException;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Slim\App;
use Slim\Factory\AppFactory;

return function(ServerRequestInterface $request): App {
    // Load environment variables from .env unless already set (e.g. in production)
    if (!get_env('APP_ENV')) {
        try {
            Dotenv::createImmutable(root_path('/'))->load();
        } catch (InvalidPathException $ex) {
            exit($ex->getMessage());
        }
    }

    // Build container
    $containerBuilder = new ContainerBuilder();
    require_from_root('bootstrap/settings.php')($containerBuilder);
    require_from_root('bootstrap/dependencies.php')($containerBuilder);
    require_from_root('bootstrap/repositories.php')($containerBuilder);

    $container = $containerBuilder->build();

    // Create app
    AppFactory::setContainer($container);
    $app = AppFactory::create();

    // Resolve settings
    $settings = $container->get(SettingsInterface::class);

    $displayErrorDetails = $settings->get('displayErrorDetails');
    $logError = $settings->get('logError');
    $logErrorDetails = $settings->get('logErrorDetails');

    // Build error handler
    $logger = $container->has(LoggerInterface::class)
        ? $container->get(LoggerInterface::class)
        : null;

    $errorHandler = new JsonErrorHandler(
        $app->getCallableResolver(),
        $app->getResponseFactory(),
        $logger
    );

    // Register shutdown handler for fatal errors outside the request lifecycle
    $corsResponseEmitter = $container->get(CorsResponseEmitter::class);

    $shutdownHandler = new ShutdownHandler(
        $request,
        new CallableErrorResponder(
            static function($request, $exception, bool $displayErrorDetails) use ($errorHandler, $logError, $logErrorDetails) {
                return $errorHandler($request, $exception, $displayErrorDetails, $logError, $logErrorDetails);
            }
        ),
        new CallableResponseEmitter(
            static function($response) use ($corsResponseEmitter): void {
                $corsResponseEmitter->emit($response);
            }
        ),
        $displayErrorDetails
    );

    register_shutdown_function($shutdownHandler);

    // Middleware
    require_from_root('bootstrap/middleware.php')($app, $errorHandler);

    // Routes
    require_from_root('bootstrap/routes.php')($app);

    return $app;
};
