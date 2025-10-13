<?php

use DI\Container;
use Monolog\Logger;

/**
 * Configure application settings from environment variables.
 *
 * Creates application configuration in the container with sensible
 * defaults for all settings when environment values are missing.
 *
 * @param Container $container The dependency injection container
 * @return void
 */
return function(Container $container) {
    $container->set('settings', function() {
        return [
            'app' => [
                'display_error_details' => (bool)get_env('APP_DISPLAY_ERROR_DETAILS', false),
                'log_errors' => (bool)get_env('APP_LOG_ERRORS', true),
                'log_error_details' => (bool)get_env('APP_LOG_ERROR_DETAILS', true),
            ],
            'database' => [
                'driver' => get_env('DB_DRIVER'),
                'host' => get_env('DB_HOST'),
                'port' => get_env('DB_PORT'),
                'database' => get_env('DB_DATABASE'),
                'username' => get_env('DB_USERNAME'),
                'password' => get_env('DB_PASSWORD'),
                'charset' => get_env('DB_CHARSET'),
                'collation' => get_env('DB_COLLATION'),
                'prefix' => '',
            ],
            'logger' => [
                'name' => get_env('LOGGER_NAME', 'app'),
                'handler' => [
                    'level' => Logger::toMonologLevel(get_env('LOGGER_HANDLER_LEVEL', 'DEBUG')),
                    'max_files' => (int)get_env('LOGGER_HANDLER_MAX_FILES', 30),
                ],
                'formatter' => [
                    'format' => get_env('LOGGER_FORMATTER_FORMAT', "[%datetime%] %level_name%: %message% %context% %extra%\n"),
                    'date_format' => get_env('LOGGER_FORMATTER_DATE_FORMAT', 'Y-m-d H:i:s'),
                    'allow_inline_line_breaks' => (bool)get_env('LOGGER_FORMATTER_ALLOW_INLINE_LINE_BREAKS', true),
                    'ignore_empty_context_and_extra' => (bool)get_env('LOGGER_FORMATTER_IGNORE_EMPTY_CONTEXT_AND_EXTRA', true),
                    'include_stack_traces' => (bool)get_env('LOGGER_FORMATTER_INCLUDE_STACK_TRACES', false)
                ],
            ],
            'view' => [
                'cache' => get_env('VIEW_CACHE_DISABLED') ? false : base_path('storage/views'),
            ],
        ];
    });
};
