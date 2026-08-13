<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Repositories\RoleRepositoryInterface;
use App\Infrastructure\Persistence\Models\EloquentUserModel;

/**
 * Manages retrieval of role assignments through Eloquent.
 */
final class EloquentRoleRepository implements RoleRepositoryInterface
{
    /**
     * Returns the names of the roles assigned to a user.
     *
     * @param  int      $userId The unique identifier of the user.
     * @return string[] The names of the roles assigned to the user.
     */
    public function findNamesForUser(int $userId): array
    {
        $user = EloquentUserModel::find($userId);

        if (null === $user) {
            return [];
        }

        return $user->roles()->orderBy('roles.name')->pluck('roles.name')->all();
    }
}
