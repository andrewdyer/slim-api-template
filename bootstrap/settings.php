<?php

declare(strict_types=1);

use AndrewDyer\Settings\Contracts\SettingsInterface;
use AndrewDyer\Settings\Settings;
use DI\ContainerBuilder;
use Monolog\Logger;

/**
 * Defines application configuration settings.
 */
return function(ContainerBuilder $containerBuilder): void {
    $containerBuilder->addDefinitions([
        SettingsInterface::class => function() {
            return new Settings([
                'displayErrorDetails' => (bool)get_env('APP_DEBUG', false),
                'logError' => (bool)get_env('APP_LOG_ERRORS', true),
                'logErrorDetails' => (bool)get_env('APP_LOG_ERROR_DETAILS', true),
                'app' => [
                    'name' => get_env('APP_NAME', 'Skeleton'),
                    'env' => get_env('APP_ENV', 'production'),
                    'key' => get_env('APP_KEY'),
                    'url' => get_env('APP_URL', 'https://localhost:8888'),
                ],
                'cors' => [
                    'allowedOrigins' => get_env_array('CORS_ALLOWED_ORIGINS'),
                ],
                'db' => [
                    'driver' => get_env('DB_DRIVER'),
                    'host' => get_env('DB_HOST'),
                    'port' => get_env('DB_PORT'),
                    'database' => get_env('DB_DATABASE'),
                    'username' => get_env('DB_USERNAME'),
                    'password' => get_env('DB_PASSWORD'),
                    'charset' => get_env('DB_CHARSET'),
                    'collation' => get_env('DB_COLLATION'),
                ],
                'logger' => [
                    'name' => get_env('LOGGER_NAME', 'app'),
                    'handler' => [
                        'level' => Logger::toMonologLevel(get_env('LOGGER_HANDLER_LEVEL', 'DEBUG')),
                        'maxFiles' => (int)get_env('LOGGER_HANDLER_MAX_FILES', 30),
                    ],
                    'formatter' => [
                        'format' => get_env('LOGGER_FORMATTER_FORMAT', "[%datetime%] %level_name%: %message% %context% %extra%\n"),
                        'dateFormat' => get_env('LOGGER_FORMATTER_DATE_FORMAT', 'Y-m-d H:i:s'),
                        'allowInlineLineBreaks' => (bool)get_env('LOGGER_FORMATTER_ALLOW_INLINE_LINE_BREAKS', true),
                        'ignoreEmptyContextAndExtra' => (bool)get_env('LOGGER_FORMATTER_IGNORE_EMPTY_CONTEXT_AND_EXTRA', true),
                        'includeStackTraces' => (bool)get_env('LOGGER_FORMATTER_INCLUDE_STACK_TRACES', false),
                    ],
                ],
            ]);
        },
    ]);
};
