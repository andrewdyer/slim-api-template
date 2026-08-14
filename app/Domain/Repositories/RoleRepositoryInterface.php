<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

/**
 * Manages retrieval of role assignments.
 */
interface RoleRepositoryInterface
{
    /**
     * Returns the names of the roles assigned to a user.
     *
     * @param  int      $userId The unique identifier of the user.
     * @return string[] The names of the roles assigned to the user.
     */
    public function findNamesForUser(int $userId): array;
}
