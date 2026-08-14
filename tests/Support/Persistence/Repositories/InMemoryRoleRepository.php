<?php

declare(strict_types=1);

namespace Tests\Support\Persistence\Repositories;

use App\Domain\Repositories\RoleRepositoryInterface;

/**
 * Manages role assignments in memory.
 *
 * Intended for development and testing purposes. Data does not persist between requests.
 */
final class InMemoryRoleRepository implements RoleRepositoryInterface
{
    /**
     * The in-memory store of role names, keyed by user ID.
     *
     * @var array<int, string[]>
     */
    private array $roles = [];

    /**
     * Builds a role assignment for a user in the in-memory store.
     *
     * @param int    $userId The unique identifier of the user.
     * @param string $role   The name of the role to assign.
     */
    public function assign(int $userId, string $role): void
    {
        $this->roles[$userId][] = $role;
    }

    /**
     * Returns the names of the roles assigned to a user.
     *
     * @param  int      $userId The unique identifier of the user.
     * @return string[] The names of the roles assigned to the user.
     */
    public function findNamesForUser(int $userId): array
    {
        return $this->roles[$userId] ?? [];
    }
}
