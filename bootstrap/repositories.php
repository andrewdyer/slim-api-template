<?php

declare(strict_types=1);

use App\Domain\Repositories\UserRepositoryInterface;
use App\Infrastructure\Persistence\Repositories\EloquentUserRepository;
use DI\ContainerBuilder;

/**
 * Binds domain interfaces to their infrastructure implementations.
 */
return static function(ContainerBuilder $containerBuilder): void {
    $containerBuilder->addDefinitions([
        UserRepositoryInterface::class => new EloquentUserRepository(),
    ]);
};
