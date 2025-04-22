<?php

/**
 * Registers shared services and dependencies in the dependency
 * injection container.
 *
 * These may include loggers, templating engines, database clients,
 * external APIs, or any custom service classes used throughout
 * the application.
 *
 * To register a new service:
 * $container->set(MyService::class, function($container) {
 *     $config = $container->get('settings')['my_service'];
 *     return new MyService($config);
 * });
 */

use DI\Container;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Slim\Views\Twig;

return function(Container $container) {
    $container->set(Logger::class, function($container) {
        $settings = $container->get('settings')['logger'];

        $logger = new Logger($settings['name']);

        $handler = new RotatingFileHandler(
            root_path('storage/logs/app.log'),
            $settings['max_files'],
            $settings['level']
        );

        $formatter = new LineFormatter(
            "[%datetime%] %level_name%: %message% %context% %extra%\n",
            'Y-m-d H:i:s'
        );

        $handler->setFormatter($formatter);

        $logger->pushHandler($handler);

        return $logger;
    });

    $container->set(Twig::class, function($container) {
        $settings = $container->get('settings')['view'];

        return Twig::create(
            root_path('resources/views'),
            ['cache' => $settings['cache']]
        );
    });
};
