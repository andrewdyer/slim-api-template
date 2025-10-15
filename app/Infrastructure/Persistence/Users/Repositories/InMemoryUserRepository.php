<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Users\Repositories;

use App\Domain\Users\Entities\User;
use App\Domain\Users\Repositories\UserRepository;

/**
 * In-memory implementation of the UserRepository interface.
 *
 * A simple in-memory implementation of the UserRepository interface.
 * Primarily intended for testing, prototyping, or demonstration purposes
 * where persistence to an actual database is not required.
 *
 * This repository uses an internal array as its data store and provides
 * basic CRUD operations on User entities.
 */
final class InMemoryUserRepository implements UserRepository
{
    /**
     * Auto-incrementing ID counter.
     *
     * @var int
     */
    private int $nextId = 1;
    /**
     * Internal in-memory data store.
     *
     * @var array<int, User> Array of User entities keyed by their ID
     */
    private array $store = [];

    /**
     * InMemoryUserRepository constructor.
     *
     * Preloads the repository with a couple of example users
     * for testing or demonstration purposes.
     */
    public function __construct()
    {
        $this->create('Bill', 'Gates', 'billgates@example.com');
        $this->create('Steve', 'Jobs', 'stevejobs@example.com');
        $this->create('Mark', 'Zuckerberg', 'markzuckerberg@example.com');
        $this->create('Evan', 'Spiegel', 'evanspiegel@example.com');
        $this->create('Jack', 'Dorsey', 'jackdorsey@example.com');
    }

    /**
     * Create a new User entity and add it to the store.
     *
     * @param string $firstName The user's first name
     * @param string $lastName  The user's last name
     * @param string $email     The user's email address
     *
     * @return User The newly created User entity
     */
    public function create(string $firstName, string $lastName, string $email): User
    {
        $user = new User($this->nextId++, $firstName, $lastName, $email);
        $this->store[$user->getId()] = $user;

        return $user;
    }

    /**
     * Delete a user from the store by ID.
     *
     * @param int $id The ID of the user to delete
     *
     * @return bool True if the user was deleted, false if not found
     */
    public function delete(int $id): bool
    {
        if (!isset($this->store[$id])) {
            return false;
        }

        unset($this->store[$id]);

        return true;
    }

    /**
     * Retrieve all users from the store.
     *
     * @return User[] A sequential array of User entities
     */
    public function findAll(): array
    {
        return array_values($this->store);
    }

    /**
     * Find a user by their ID.
     *
     * @param int $id The ID of the user to find
     *
     * @return User|null The User entity if found, otherwise null
     */
    public function findById(int $id): ?User
    {
        return $this->store[$id] ?? null;
    }

    /**
     * Update an existing user's information.
     *
     * Only non-null fields will be updated. Fields passed as null
     * will retain their existing values.
     *
     * @param int         $id        The ID of the user to update
     * @param string|null $firstName The new first name (optional)
     * @param string|null $lastName  The new last name (optional)
     * @param string|null $email     The new email (optional)
     *
     * @return User|null The updated User entity, or null if the user was not found
     */
    public function update(int $id, ?string $firstName, ?string $lastName, ?string $email): ?User
    {
        $existing = $this->store[$id] ?? null;

        if (null === $existing) {
            return null;
        }

        $updated = new User(
            id: $id,
            firstName: $firstName ?? $existing->getFirstName(),
            lastName: $lastName ?? $existing->getLastName(),
            email: $email ?? $existing->getEmail()
        );

        $this->store[$id] = $updated;

        return $updated;
    }
}
