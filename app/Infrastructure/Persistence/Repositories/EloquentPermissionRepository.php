<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Repositories\PermissionRepositoryInterface;
use App\Infrastructure\Persistence\Models\EloquentUserModel;

/**
 * Manages retrieval of permission assignments through Eloquent.
 */
final class EloquentPermissionRepository implements PermissionRepositoryInterface
{
    /**
     * Returns the names of the permissions granted to a user through their assigned roles.
     *
     * @param  int      $userId The unique identifier of the user.
     * @return string[] The names of the permissions granted to the user.
     */
    public function findNamesForUser(int $userId): array
    {
        $user = EloquentUserModel::find($userId);

        if (null === $user) {
            return [];
        }

        return $user->permissions()->pluck('name')->sort()->values()->all();
    }
}
