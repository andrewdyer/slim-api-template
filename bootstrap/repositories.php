<?php

declare(strict_types=1);

use App\Domain\User\UserRepository;
use App\Infrastructure\Persistence\User\InMemoryUserRepository;
use DI\ContainerBuilder;

return function(ContainerBuilder $containerBuilder): void {
    $containerBuilder->addDefinitions([
        UserRepository::class => new InMemoryUserRepository(),
    ]);
};
