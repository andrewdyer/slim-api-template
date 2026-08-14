<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

/**
 * Manages retrieval of permission assignments.
 */
interface PermissionRepositoryInterface
{
    /**
     * Returns the names of the permissions granted to a user through their assigned roles.
     *
     * @param  int      $userId The unique identifier of the user.
     * @return string[] The names of the permissions granted to the user.
     */
    public function findNamesForUser(int $userId): array;
}
