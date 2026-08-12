<?php

declare(strict_types=1);

use AndrewDyer\Settings\Contracts\SettingsInterface;
use DI\ContainerBuilder;
use Illuminate\Database\Capsule\Manager as Capsule;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 * Registers service dependencies in the container.
 */
return static function(ContainerBuilder $containerBuilder): void {
    $containerBuilder->addDefinitions([
        Capsule::class => static function(ContainerInterface $container) {
            $settings = $container->get(SettingsInterface::class);

            $capsule = new Capsule();
            $capsule->addConnection($settings->get('db'));
            $capsule->setAsGlobal();

            return $capsule;
        },
        LoggerInterface::class => static function(ContainerInterface $container) {
            $settings = $container->get(SettingsInterface::class);

            $logger = new Logger($settings->get('logger.name'));

            $handler = new RotatingFileHandler(
                filename: root_path('storage/logs/app.log'),
                maxFiles: $settings->get('logger.handler.maxFiles'),
                level: $settings->get('logger.handler.level')
            );

            $formatter = new LineFormatter(
                format: $settings->get('logger.formatter.format'),
                dateFormat: $settings->get('logger.formatter.dateFormat'),
                allowInlineLineBreaks: $settings->get('logger.formatter.allowInlineLineBreaks'),
                ignoreEmptyContextAndExtra: $settings->get('logger.formatter.ignoreEmptyContextAndExtra'),
                includeStacktraces: $settings->get('logger.formatter.includeStackTraces')
            );

            $handler->setFormatter($formatter);

            $logger->pushHandler($handler);

            return $logger;
        },
    ]);
};
