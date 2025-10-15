<?php

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
        $settings = $container->get('settings')['logger'];

        $logger = new Logger($settings['name']);

        $handler = new RotatingFileHandler(
            base_path('storage/logs/app.log'),
            $settings['handler']['max_files'],
            $settings['handler']['level']
        );

        $formatter = new LineFormatter(
            $settings['formatter']['format'],
            $settings['formatter']['date_format'],
            $settings['formatter']['allow_inline_line_breaks'],
            $settings['formatter']['ignore_empty_context_and_extra'],
            $settings['formatter']['include_stack_traces']
        );

        $handler->setFormatter($formatter);

        $logger->pushHandler($handler);

        return $logger;
    });
};
