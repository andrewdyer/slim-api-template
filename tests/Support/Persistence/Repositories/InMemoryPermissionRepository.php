<?php

declare(strict_types=1);

namespace Tests\Support\Persistence\Repositories;

use App\Domain\Repositories\PermissionRepositoryInterface;

/**
 * Manages permission assignments in memory.
 *
 * Intended for development and testing purposes. Data does not persist between requests.
 */
final class InMemoryPermissionRepository implements PermissionRepositoryInterface
{
    /**
     * The in-memory store of permission names, keyed by user ID.
     *
     * @var array<int, string[]>
     */
    private array $permissions = [];

    /**
     * Returns the names of the permissions granted to a user through their assigned roles.
     *
     * @param  int      $userId The unique identifier of the user.
     * @return string[] The names of the permissions granted to the user.
     */
    public function findNamesForUser(int $userId): array
    {
        return $this->permissions[$userId] ?? [];
    }

    /**
     * Builds a permission grant for a user in the in-memory store.
     *
     * @param int    $userId     The unique identifier of the user.
     * @param string $permission The name of the permission to grant.
     */
    public function grant(int $userId, string $permission): void
    {
        $this->permissions[$userId][] = $permission;
    }
}
