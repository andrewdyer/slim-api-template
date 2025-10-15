<?php

namespace App\Domain\Users\Repositories;

use App\Domain\Users\Entities\User;

/**
 * Contract for user persistence and retrieval operations.
 *
 * This abstraction allows the domain and application layers to remain
 * agnostic of the underlying data storage mechanism — whether it be
 * a database, external service, or in-memory data source.
 */
interface UserRepository
{
    /**
     * Create a new user entity and persist it to storage.
     *
     * @param string $firstName The user's first name
     * @param string $lastName  The user's last name
     * @param string $email     The user's email address
     *
     * @return User The newly created user entity
     */
    public function create(string $firstName, string $lastName, string $email): User;

    /**
     * Delete a user by their unique identifier.
     *
     * @param int $id The user's ID
     *
     * @return bool True if the user was successfully deleted, false if not found
     */
    public function delete(int $id): bool;

    /**
     * Retrieve all user entities from storage.
     *
     * @return User[] A sequential array of all User entities
     */
    public function findAll(): array;

    /**
     * Find a specific user by their unique identifier.
     *
     * @param int $id The user's ID
     *
     * @return User|null The corresponding User entity, or null if not found
     */
    public function findById(int $id): ?User;

    /**
     * Update an existing user's information.
     *
     * Any provided non-null fields will replace existing values;
     * null fields will retain their current values.
     *
     * @param int         $id        The ID of the user to update
     * @param string|null $firstName The updated first name (optional)
     * @param string|null $lastName  The updated last name (optional)
     * @param string|null $email     The updated email (optional)
     *
     * @return User|null The updated User entity, or null if no matching user exists
     */
    public function update(int $id, ?string $firstName, ?string $lastName, ?string $email): ?User;
}
