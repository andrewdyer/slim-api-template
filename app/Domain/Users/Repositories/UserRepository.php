<?php

namespace App\Domain\Users\Repositories;

use App\Domain\Users\Entities\User;

/**
 * Interface UserRepository.
 *
 * Defines a contract for user persistence and retrieval operations.
 *
 * This abstraction allows the domain and application layers to remain
 * agnostic of the underlying data storage mechanism — whether it be
 * a database, external service, or in-memory data source.
 *
 * Implementations might include:
 *  - InMemoryUserRepository (for testing or prototyping)
 *  - EloquentUserRepository (for database-backed persistence)
 *  - ApiUserRepository (for remote data sources)
 */
interface UserRepository
{
    /**
     * Create a new user entity and persist it to storage.
     *
     * @param string $firstName the user's first name
     * @param string $lastName  the user's last name
     * @param string $email     the user's email address
     *
     * @return User the newly created user entity
     */
    public function create(string $firstName, string $lastName, string $email): User;

    /**
     * Delete a user by their unique identifier.
     *
     * @param int $id the user's ID
     *
     * @return bool true if the user was successfully deleted, false if not found
     */
    public function delete(int $id): bool;

    /**
     * Retrieve all user entities from storage.
     *
     * @return User[] a sequential array of all User entities
     */
    public function findAll(): array;

    /**
     * Find a specific user by their unique identifier.
     *
     * @param int $id the user's ID
     *
     * @return User|null the corresponding User entity, or null if not found
     */
    public function findById(int $id): ?User;

    /**
     * Update an existing user's information.
     *
     * Any provided non-null fields will replace existing values;
     * null fields will retain their current values.
     *
     * @param int         $id        the ID of the user to update
     * @param string|null $firstName the updated first name (optional)
     * @param string|null $lastName  the updated last name (optional)
     * @param string|null $email     the updated email (optional)
     *
     * @return User|null the updated User entity, or null if no matching user exists
     */
    public function update(int $id, ?string $firstName, ?string $lastName, ?string $email): ?User;
}
