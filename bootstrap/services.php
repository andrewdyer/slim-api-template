<?php

use DI\Container;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

return function(Container $container) {
    $container->set(Logger::class, function($container) {
        $settings = $container->get('settings')['logger'];

        $logger = new Logger($settings['name']);
        $handler = new RotatingFileHandler(root_path('storage/logs/app.log'), $settings['max_files'], $settings['level']);

        $formatter = new LineFormatter(
            "[%datetime%] %level_name%: %message% %context% %extra%\n",
            'Y-m-d H:i:s'
        );
        $handler->setFormatter($formatter);

        $logger->pushHandler($handler);

        return $logger;
    });

    return $container;
};
