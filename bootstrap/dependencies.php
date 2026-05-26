<?php

declare(strict_types=1);

use AndrewDyer\CommandBus\CommandBus;
use AndrewDyer\Settings\Contracts\SettingsInterface;
use App\Application\Users\Commands\CreateUserCommand;
use App\Application\Users\Commands\DeleteUserCommand;
use App\Application\Users\Commands\UpdateUserCommand;
use App\Application\Users\Handlers\CreateUserHandler;
use App\Application\Users\Handlers\DeleteUserHandler;
use App\Application\Users\Handlers\UpdateUserHandler;
use App\Domain\User\UserRepository;
use DI\ContainerBuilder;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 * Registers service dependencies in the container.
 */
return function(ContainerBuilder $containerBuilder): void {
    $containerBuilder->addDefinitions([
        CommandBus::class => function(ContainerInterface $container) {
            $userRepository = $container->get(UserRepository::class);

            $bus = new CommandBus();

            $bus->register(CreateUserCommand::class, new CreateUserHandler($userRepository));
            $bus->register(DeleteUserCommand::class, new DeleteUserHandler($userRepository));
            $bus->register(UpdateUserCommand::class, new UpdateUserHandler($userRepository));

            return $bus;
        },
        LoggerInterface::class => function(ContainerInterface $container) {
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
