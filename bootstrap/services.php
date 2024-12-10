<?php

use DI\Container;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

return function(Container $container) {
    $container->set('settings', function() {
        return [
            'app' => [
                'display_error_details' => get_env_bool('APP_DISPLAY_ERROR_DETAILS'),
                'log_errors'            => get_env_bool('APP_LOG_ERRORS'),
                'log_error_details'     => get_env_bool('APP_LOG_ERROR_DETAILS'),
            ],
            'logger' => [
                'name'        => get_env('LOG_NAME', 'app'),
                'path'        => root_path('storage/logs/app.log'),
                'level'       => Logger::toMonologLevel(get_env('LOG_LEVEL', 'DEBUG')),
                'max_files'   => get_env_int('LOG_MAX_FILES', 30),
                'log_format'  => get_env('LOG_FORMAT', "[%datetime%] %level_name%: %message% %context% %extra%\n"),
                'date_format' => get_env('LOG_DATE_FORMAT', 'Y-m-d H:i:s'),
            ],
        ];
    });

    $container->set(Logger::class, function($container) {
        $settings = $container->get('settings')['logger'];

        $logger = new Logger($settings['name']);
        $handler = new RotatingFileHandler($settings['path'], $settings['max_files'], $settings['level']);

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
