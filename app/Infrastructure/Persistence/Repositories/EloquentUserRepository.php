<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Exceptions\DuplicateEmailException;
use App\Domain\Models\User;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Infrastructure\Persistence\Models\EloquentUserModel;
use Illuminate\Database\QueryException;

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
        try {
            $model = EloquentUserModel::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
            ]);
        } catch (QueryException $e) {
            throw $this->isDuplicateEmailViolation($e)
                ? new DuplicateEmailException("A user with email {$email} already exists.", previous: $e)
                : $e;
        }

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
     * Determines whether a query exception was caused by the email uniqueness constraint.
     *
     * SQLSTATE 23000 is the broad "integrity constraint violation" class, which also
     * covers NOT NULL and foreign key violations, so it isn't specific enough on its own.
     * MySQL's driver-specific error code 1062 (ER_DUP_ENTRY) is used instead, since the
     * users table has exactly one unique index and this repository is MySQL-only.
     *
     * @param  QueryException $e The query exception to inspect.
     * @return bool           True if the exception represents a duplicate-entry violation.
     */
    private function isDuplicateEmailViolation(QueryException $e): bool
    {
        return ($e->errorInfo[1] ?? null) === 1062;
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

        if (null !== $firstName) {
            $model->first_name = $firstName;
        }

        if (null !== $lastName) {
            $model->last_name = $lastName;
        }

        if (null !== $email) {
            $model->email = $email;
        }

        try {
            $model->save();
        } catch (QueryException $e) {
            throw $this->isDuplicateEmailViolation($e)
                ? new DuplicateEmailException("A user with email {$email} already exists.", previous: $e)
                : $e;
        }

        return $this->toDomain($model);
    }
}
