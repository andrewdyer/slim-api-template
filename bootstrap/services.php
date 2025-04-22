<?php

/*
 * Registers shared services and dependencies in the dependency
 * injection container.
 *
 * These may include loggers, templating engines, database clients,
 * external APIs, or any custom service classes used throughout
 * the application.
 *
 * Each service should be defined in `app/Services` and registered
 * here with its required configuration or dependencies, often
 * sourced from the settings defined in `settings.php`.
 *
 * To register a new service:
 * $container->set(MyService::class, function () use ($container) {
 *     $config = $container->get('settings')['my_service'];
 *     return new MyService($config);
 * });
 */

use App\Services\LoggerService;
use App\Services\TwigService;
use DI\Container;
use Monolog\Logger;
use Slim\Views\Twig;

return function(Container $container) {
    $container->set(Logger::class, function($container) {
        $settings = $container->get('settings')['logger'];

        return (new LoggerService($settings))();
    });

    $container->set(Twig::class, function($container) {
        $settings = $container->get('settings')['view'];

        return (new TwigService($settings))();
    });
};
