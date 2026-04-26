<?php

declare(strict_types=1);

namespace App\Infrastructure\Factory;

use AndrewDyer\CorsResponseEmitter\CorsResponseEmitter;
use AndrewDyer\JsonErrorHandler\JsonErrorHandler;
use AndrewDyer\Settings\Contracts\SettingsInterface;
use AndrewDyer\ShutdownHandler\Adapters\CallableErrorResponder;
use AndrewDyer\ShutdownHandler\Adapters\CallableResponseEmitter;
use AndrewDyer\ShutdownHandler\ShutdownHandler;
use App\Infrastructure\Application;
use DI\Container;
use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Slim\App;
use Slim\Factory\AppFactory;

/**
 * Application factory responsible for building the Slim application.
 */
final class ApplicationFactory
{
    /**
     * Creates the configured application instance.
     *
     * This is the public entry point for bootstrapping the application.
     *
     * @param  ServerRequestInterface $request The incoming server request.
     * @return Application            Returns the configured application.
     * @throws InvalidPathException   When the environment file cannot be loaded.
     */
    public static function create(ServerRequestInterface $request): Application
    {
        return (new self())->build($request);
    }

    /**
     * Builds the configured application instance.
     *
     * Orchestrates container creation, middleware registration, and routing.
     *
     * @param  ServerRequestInterface $request The incoming server request.
     * @return Application            Returns the configured application.
     * @throws InvalidPathException   When the environment file cannot be loaded.
     *
     * @internal
     */
    private function build(ServerRequestInterface $request): Application
    {
        $this->loadEnvironment();

        $container = $this->buildContainer();

        $app = $this->createApp($container);

        $settings = $this->getSettings($container);

        $errorHandler = $this->createErrorHandler($app, $container);

        $emitter = $this->createEmitter($settings);

        $this->registerShutdownHandler(
            $request,
            $errorHandler,
            $emitter,
            $settings
        );

        $this->registerMiddleware($app, $settings, $errorHandler);

        $this->registerRoutes($app);

        return new Application($app, $emitter);
    }

    /**
     * Loads environment variables when not already set.
     *
     * @return void                 Returns after environment variables are loaded.
     * @throws InvalidPathException When the environment file cannot be loaded.
     */
    private function loadEnvironment(): void
    {
        if (!get_env('APP_ENV')) {
            Dotenv::createImmutable(root_path('/'))->load();
        }
    }

    /**
     * Builds the dependency injection container.
     *
     * @return Container Returns the configured container.
     */
    private function buildContainer(): Container
    {
        $builder = new ContainerBuilder();

        require_from_root('bootstrap/settings.php')($builder);
        require_from_root('bootstrap/dependencies.php')($builder);
        require_from_root('bootstrap/repositories.php')($builder);

        return $builder->build();
    }

    /**
     * Creates the Slim application instance.
     *
     * @param  Container $container The dependency injection container.
     * @return App       Returns the Slim application.
     */
    private function createApp(Container $container): App
    {
        AppFactory::setContainer($container);

        return AppFactory::create();
    }

    /**
     * Returns the application settings.
     *
     * @param  Container         $container The dependency injection container.
     * @return SettingsInterface Returns the application settings.
     */
    private function getSettings(Container $container): SettingsInterface
    {
        return $container->get(SettingsInterface::class);
    }

    /**
     * Creates the JSON error handler instance.
     *
     * @param  App              $app       The Slim application.
     * @param  Container        $container The dependency injection container.
     * @return JsonErrorHandler Returns the error handler.
     */
    private function createErrorHandler(App $app, Container $container): JsonErrorHandler
    {
        $logger = $container->has(LoggerInterface::class)
            ? $container->get(LoggerInterface::class)
            : null;

        return new JsonErrorHandler(
            $app->getCallableResolver(),
            $app->getResponseFactory(),
            $logger
        );
    }

    /**
     * Creates the CORS response emitter.
     *
     * @param  SettingsInterface   $settings The application settings.
     * @return CorsResponseEmitter Returns the response emitter.
     */
    private function createEmitter(SettingsInterface $settings): CorsResponseEmitter
    {
        return new CorsResponseEmitter(
            $settings->get('cors.allowedOrigins')
        );
    }

    /**
     * Registers the shutdown handler for fatal errors.
     *
     * @param  ServerRequestInterface $request      The incoming server request.
     * @param  JsonErrorHandler       $errorHandler The error handler.
     * @param  CorsResponseEmitter    $emitter      The response emitter.
     * @param  SettingsInterface      $settings     The application settings.
     * @return void                   Returns after the shutdown handler is registered.
     */
    private function registerShutdownHandler(
        ServerRequestInterface $request,
        JsonErrorHandler $errorHandler,
        CorsResponseEmitter $emitter,
        SettingsInterface $settings
    ): void {
        $displayErrorDetails = $settings->get('displayErrorDetails');
        $logError = $settings->get('logError');
        $logErrorDetails = $settings->get('logErrorDetails');

        $shutdownHandler = new ShutdownHandler(
            $request,
            new CallableErrorResponder(
                static function($request, $exception, bool $displayErrorDetails) use ($errorHandler, $logError, $logErrorDetails) {
                    return $errorHandler($request, $exception, $displayErrorDetails, $logError, $logErrorDetails);
                }
            ),
            new CallableResponseEmitter(
                static function($response) use ($emitter): void {
                    $emitter->emit($response);
                }
            ),
            $displayErrorDetails
        );

        register_shutdown_function($shutdownHandler);
    }

    /**
     * Registers middleware on the Slim application.
     *
     * @param  App               $app          The Slim application.
     * @param  SettingsInterface $settings     The application settings.
     * @param  JsonErrorHandler  $errorHandler The error handler.
     * @return void              Returns after middleware is registered.
     */
    private function registerMiddleware(
        App $app,
        SettingsInterface $settings,
        JsonErrorHandler $errorHandler
    ): void {
        $displayErrorDetails = $settings->get('displayErrorDetails');
        $logError = $settings->get('logError');
        $logErrorDetails = $settings->get('logErrorDetails');

        $app->addBodyParsingMiddleware();
        $app->addRoutingMiddleware();

        require_from_root('bootstrap/middleware.php')($app);

        $errorMiddleware = $app->addErrorMiddleware(
            $displayErrorDetails,
            $logError,
            $logErrorDetails
        );

        $errorMiddleware->setDefaultErrorHandler($errorHandler);
    }

    /**
     * Registers routes on the Slim application.
     *
     * @param  App  $app The Slim application.
     * @return void Returns after routes are registered.
     */
    private function registerRoutes(App $app): void
    {
        require_from_root('bootstrap/routes.php')($app);
    }
}
