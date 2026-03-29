<?php

declare(strict_types=1);

use AndrewDyer\Settings\Contracts\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $container) {
            $settings = $container->get(SettingsInterface::class);

            $logger = new Logger($settings->get('logger.name'));

            $handler = new RotatingFileHandler(
                root_path('storage/logs/app.log'),
                $settings->get('logger.handler.maxFiles'),
                $settings->get('logger.handler.level')
            );

            $formatter = new LineFormatter(
                $settings->get('logger.formatter.format'),
                $settings->get('logger.formatter.dateFormat'),
                $settings->get('logger.formatter.allowInlineLineBreaks'),
                $settings->get('logger.formatter.ignoreEmptyContextAndExtra'),
                $settings->get('logger.formatter.includeStackTraces')
            );

            $handler->setFormatter($formatter);

            $logger->pushHandler($handler);

            return $logger;
        }
    ]);
};
