<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Exceptions\DuplicateEmailException;
use App\Domain\Models\User;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Infrastructure\Persistence\Models\EloquentUserModel;

/**
 * Manages user persistence and retrieval through Eloquent.
 *
 * Uses Illuminate Database Capsule to persist and retrieve user entities from a database.
 */
final class EloquentUserRepository implements UserRepositoryInterface
{
    /**
     * Creates and persists a new user with the given details.
     *
     * @param  string                  $firstName The user's first name.
     * @param  string                  $lastName  The user's last name.
     * @param  string                  $email     The user's email address.
     * @return User                    The newly created User entity.
     * @throws DuplicateEmailException If a user with the given email already exists.
     */
    public function create(string $firstName, string $lastName, string $email): User
    {
        if (EloquentUserModel::where('email', $email)->exists()) {
            throw new DuplicateEmailException("A user with email {$email} already exists.");
        }

        $model = EloquentUserModel::create([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
        ]);

        return $this->toDomain($model);
    }

    /**
     * Deletes the user with the given ID.
     *
     * @param  int  $id The unique identifier of the user to delete.
     * @return bool True if the user was deleted, false if no user with that ID existed.
     */
    public function delete(int $id): bool
    {
        $model = EloquentUserModel::find($id);

        if (null === $model) {
            return false;
        }

        return (bool)$model->delete();
    }

    /**
     * Returns all stored user entities.
     *
     * @return User[] An array of all User entities.
     */
    public function findAll(): array
    {
        return EloquentUserModel::all()
            ->map(fn (EloquentUserModel $model) => $this->toDomain($model))
            ->all();
    }

    /**
     * Returns a user by their unique identifier.
     *
     * @param  int       $id The unique identifier of the user to retrieve.
     * @return User|null The matching User entity, or null if not found.
     */
    public function findById(int $id): ?User
    {
        $model = EloquentUserModel::find($id);

        if (null === $model) {
            return null;
        }

        return $this->toDomain($model);
    }

    /**
     * Returns a paginated collection of persisted user entities.
     *
     * @param  int                                  $page    The page number to retrieve.
     * @param  int                                  $perPage The maximum number of users to include per page.
     * @return array{users: list<User>, total: int} The users on the requested page and the total user count.
     */
    public function findPaginated(int $page, int $perPage): array
    {
        $page = max(1, $page);
        $perPage = min(max(1, $perPage), 100);

        $query = EloquentUserModel::query()->orderBy('id');

        $total = (clone $query)->count();

        $users = $query
            ->forPage($page, $perPage)
            ->get()
            ->map(fn (EloquentUserModel $model): User => $this->toDomain($model))
            ->all();

        return [
            'users' => $users,
            'total' => $total,
        ];
    }

    /**
     * Builds a domain User entity from an Eloquent model.
     *
     * @param  EloquentUserModel $model The Eloquent model to convert.
     * @return User              The corresponding domain entity.
     */
    private function toDomain(EloquentUserModel $model): User
    {
        return new User(
            id: $model->id,
            firstName: $model->first_name,
            lastName: $model->last_name,
            email: $model->email
        );
    }

    /**
     * Updates an existing user's details and returns the updated entity.
     *
     * @param  int                     $id        The unique identifier of the user to update.
     * @param  string|null             $firstName The new first name, or null to leave unchanged.
     * @param  string|null             $lastName  The new last name, or null to leave unchanged.
     * @param  string|null             $email     The new email address, or null to leave unchanged.
     * @return User|null               The updated User entity, or null if no user with that ID existed.
     * @throws DuplicateEmailException If another user with the given email already exists.
     */
    public function update(int $id, ?string $firstName, ?string $lastName, ?string $email): ?User
    {
        $model = EloquentUserModel::find($id);

        if (null === $model) {
            return null;
        }

        if (null !== $email && EloquentUserModel::where('email', $email)->where('id', '!=', $id)->exists()) {
            throw new DuplicateEmailException("A user with email {$email} already exists.");
        }

        if (null !== $firstName) {
            $model->first_name = $firstName;
        }

        if (null !== $lastName) {
            $model->last_name = $lastName;
        }

        if (null !== $email) {
            $model->email = $email;
        }

        $model->save();

        return $this->toDomain($model);
    }
}
