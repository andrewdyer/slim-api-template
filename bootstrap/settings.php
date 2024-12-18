<?php

use DI\Container;
use Monolog\Logger;

return function(Container $container) {
    $container->set('settings', function() {
        return [
            'app' => [
                'display_error_details' => get_env_bool('APP_DISPLAY_ERROR_DETAILS'),
                'log_errors' => get_env_bool('APP_LOG_ERRORS'),
                'log_error_details' => get_env_bool('APP_LOG_ERROR_DETAILS'),
            ],
            'logger' => [
                'name' => get_env('LOG_NAME', 'app'),
                'level' => Logger::toMonologLevel(get_env('LOG_LEVEL', 'DEBUG')),
                'max_files' => get_env_int('LOG_MAX_FILES', 30),
                'log_format' => get_env('LOG_FORMAT', "[%datetime%] %level_name%: %message% %context% %extra%\n"),
                'date_format' => get_env('LOG_DATE_FORMAT', 'Y-m-d H:i:s'),
            ],
            'view' => [
                'cache' => get_env_bool('VIEW_CACHE_DISABLED') ? false : root_path('storage/views'),
            ],
        ];
    });
};
