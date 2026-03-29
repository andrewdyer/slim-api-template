<?php

declare(strict_types=1);

use AndrewDyer\Settings\Contracts\SettingsInterface;
use AndrewDyer\Settings\Settings;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        SettingsInterface::class => function () {
            return new Settings([
                'displayErrorDetails' => (bool) get_env('APP_DEBUG', false),
                'logError' => (bool) get_env('APP_LOG_ERRORS', true),
                'logErrorDetails' => (bool) get_env('APP_LOG_ERROR_DETAILS', true),
                'app' => [
                    'name' => get_env('APP_NAME', 'Skeleton'),
                    'env' => get_env('APP_ENV', 'production'),
                    'key' => get_env('APP_KEY'),
                    'url' => get_env('APP_URL', 'https://localhost:8888'),
                ],
                'logger' => [
                    'name' => get_env('LOGGER_NAME', 'app'),
                    'formatter' => [
                        'format' => get_env('LOGGER_FORMAT', "[%datetime%] %level_name%: %message% %context% %extra%"),
                        'dateFormat' => get_env('LOGGER_DATE_FORMAT', 'Y-m-d H:i:s'),
                        'allowInlineLineBreaks' => (bool) get_env('LOGGER_ALLOW_INLINE_LINE_BREAKS', true),
                        'ignoreEmptyContextAndExtra' => (bool) get_env('LOGGER_IGNORE_EMPTY_CONTEXT_AND_EXTRA', true),
                    ],
                    'handler' => [
                        'level' => Monolog\Logger::DEBUG,
                    ],
                ],
            ]);
        }
    ]);
};