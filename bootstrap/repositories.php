<?php

declare(strict_types=1);

use App\Domain\Repositories\PermissionRepositoryInterface;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Infrastructure\Persistence\Repositories\EloquentPermissionRepository;
use App\Infrastructure\Persistence\Repositories\EloquentUserRepository;
use DI\ContainerBuilder;

/**
 * Binds domain interfaces to their infrastructure implementations.
 */
return static function(ContainerBuilder $containerBuilder): void {
    $containerBuilder->addDefinitions([
        PermissionRepositoryInterface::class => static function(): EloquentPermissionRepository {
            return new EloquentPermissionRepository();
        },
        UserRepositoryInterface::class => static function(): EloquentUserRepository {
            return new EloquentUserRepository();
        },
    ]);
};
