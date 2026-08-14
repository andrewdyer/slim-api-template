<?php

declare(strict_types=1);

namespace App\Application\Middleware;

use App\Domain\Repositories\PermissionRepositoryInterface;

/**
 * Builds RequirePermissionMiddleware instances for a specific permission.
 *
 * Middleware classes registered directly on a route are resolved by the
 * container without arguments, which does not work for a permission name.
 * Resolve this factory from the container instead and call make() with the
 * permission a route requires, e.g.:
 *
 *   $factory = $app->getContainer()->get(RequirePermissionMiddlewareFactory::class);
 *   $route->add($factory->make('users.manage'));
 */
final readonly class RequirePermissionMiddlewareFactory
{
    /**
     * Creates a new RequirePermissionMiddlewareFactory with the required dependencies.
     *
     * @param PermissionRepositoryInterface $permissions The repository used to resolve a user's permissions.
     */
    public function __construct(
        private PermissionRepositoryInterface $permissions,
    ) {
    }

    /**
     * Creates a middleware instance that requires the given permission.
     *
     * @param  string                      $permission The name of the required permission.
     * @return RequirePermissionMiddleware The configured middleware instance.
     */
    public function make(string $permission): RequirePermissionMiddleware
    {
        return new RequirePermissionMiddleware($this->permissions, $permission);
    }
}
