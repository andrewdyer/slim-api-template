<?php

declare(strict_types=1);

use App\Domain\Repositories\UserRepository;
use App\Infrastructure\Persistence\Repositories\EloquentUserRepository;
use DI\ContainerBuilder;

/**
 * Binds domain interfaces to their infrastructure implementations.
 */
return function(ContainerBuilder $containerBuilder): void {
    $containerBuilder->addDefinitions([
        UserRepository::class => new EloquentUserRepository(),
    ]);
};
