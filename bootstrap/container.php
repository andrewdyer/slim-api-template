<?php

use App\Application\Config\Settings;
use DI\Container;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

/**
 * Register application services in the dependency injection container.
 *
 * Sets up core application services using configuration values
 * defined in the settings service.
 *
 * @param Container $container The dependency injection container
 * @return void
 */
return function(Container $container) {
    $container->set(Logger::class, function($container) {
        $settings = $container->get(Settings::class);

        $loggerSettings = $settings->get('logger');

        $logger = new Logger($loggerSettings['name']);

        $handler = new RotatingFileHandler(
            root_path('storage/logs/app.log'),
            $loggerSettings['handler']['max_files'],
            $loggerSettings['handler']['level']
        );

        $formatter = new LineFormatter(
            $loggerSettings['formatter']['format'],
            $loggerSettings['formatter']['date_format'],
            $loggerSettings['formatter']['allow_inline_line_breaks'],
            $loggerSettings['formatter']['ignore_empty_context_and_extra'],
            $loggerSettings['formatter']['include_stack_traces']
        );

        $handler->setFormatter($formatter);

        $logger->pushHandler($handler);

        return $logger;
    });
};
