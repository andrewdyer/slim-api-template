<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Models\User;

/**
 * Defines the contract for persisting and retrieving user entities.
 */
interface UserRepositoryInterface
{
    /**
     * Creates and persists a new user with the given details.
     *
     * @param  string $firstName The user's first name.
     * @param  string $lastName  The user's last name.
     * @param  string $email     The user's email address.
     * @return User   The newly created User entity.
     */
    public function create(string $firstName, string $lastName, string $email): User;

    /**
     * Deletes the user with the given ID.
     *
     * @param  int  $id The unique identifier of the user to delete.
     * @return bool True if the user was deleted, false if no user with that ID existed.
     */
    public function delete(int $id): bool;

    /**
     * Returns all stored user entities.
     *
     * @return User[] An array of all User entities.
     */
    public function findAll(): array;

    /**
     * Returns a user by their unique identifier.
     *
     * @param  int       $id The unique identifier of the user to retrieve.
     * @return User|null The matching User entity, or null if not found.
     */
    public function findById(int $id): ?User;

    /**
     * Returns a paginated subset of users.
     *
     * @param  int                              $page    The page number to retrieve (1-indexed).
     * @param  int                              $perPage The number of users per page.
     * @return array{users: User[], total: int} An array containing the users for the requested page and the total count.
     */
    public function findPaginated(int $page, int $perPage): array;

    /**
     * Updates an existing user's details and returns the updated entity.
     *
     * @param  int         $id        The unique identifier of the user to update.
     * @param  string|null $firstName The new first name, or null to leave unchanged.
     * @param  string|null $lastName  The new last name, or null to leave unchanged.
     * @param  string|null $email     The new email address, or null to leave unchanged.
     * @return User|null   The updated User entity, or null if no user with that ID existed.
     */
    public function update(int $id, ?string $firstName, ?string $lastName, ?string $email): ?User;
}
