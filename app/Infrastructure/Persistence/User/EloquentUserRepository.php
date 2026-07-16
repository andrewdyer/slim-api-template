<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\User;

use App\Domain\User\User;
use App\Domain\User\UserRepository;

/**
 * Eloquent-based implementation of UserRepository.
 *
 * Uses Illuminate Database Capsule to persist and retrieve user entities from a database.
 */
final class EloquentUserRepository implements UserRepository
{
    /**
     * Creates and persists a new user with the given details.
     *
     * @param  string $firstName The user's first name.
     * @param  string $lastName  The user's last name.
     * @param  string $email     The user's email address.
     * @return User   The newly created User entity.
     */
    public function create(string $firstName, string $lastName, string $email): User
    {
        $model = UserModel::create([
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
        $model = UserModel::find($id);

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
        return UserModel::all()
            ->map(fn (UserModel $model) => $this->toDomain($model))
            ->all();
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
        $paginator = UserModel::query()
            ->orderBy('id')
            ->paginate($perPage, ['*'], 'page', $page);

        $users = array_map(
            fn (UserModel $model): User => $this->toDomain($model),
            $paginator->items(),
        );

        return [
            'users' => $users,
            'total' => $paginator->total(),
        ];
    }

    /**
     * Finds a user by their unique identifier.
     *
     * @param  int       $id The unique identifier of the user to retrieve.
     * @return User|null The matching User entity, or null if not found.
     */
    public function findById(int $id): ?User
    {
        $model = UserModel::find($id);

        if (null === $model) {
            return null;
        }

        return $this->toDomain($model);
    }

    /**
     * Updates an existing user's details and returns the updated entity.
     *
     * @param  int         $id        The unique identifier of the user to update.
     * @param  string|null $firstName The new first name, or null to leave unchanged.
     * @param  string|null $lastName  The new last name, or null to leave unchanged.
     * @param  string|null $email     The new email address, or null to leave unchanged.
     * @return User|null   The updated User entity, or null if no user with that ID existed.
     */
    public function update(int $id, ?string $firstName, ?string $lastName, ?string $email): ?User
    {
        $model = UserModel::find($id);

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

        $model->save();

        return $this->toDomain($model);
    }

    /**
     * Transforms an Eloquent UserModel into a domain User entity.
     *
     * @param  UserModel $model The Eloquent model to convert.
     * @return User      The corresponding domain entity.
     */
    private function toDomain(UserModel $model): User
    {
        return new User(
            id: $model->id,
            firstName: $model->first_name,
            lastName: $model->last_name,
            email: $model->email
        );
    }
}
