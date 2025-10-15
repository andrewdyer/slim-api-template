<?php

namespace App\Infrastructure\Persistence\Users\Repositories;

use App\Domain\Users\Entities\User;
use App\Domain\Users\Repositories\UserRepository;

/**
 * Class InMemoryUserRepository.
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
     * Internal in-memory data store.
     *
     * @var array<int, User> array of User entities keyed by their ID
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
        $this->store = [
            1 => new User(1, 'John', 'Doe', 'johndoe@example.com'),
            2 => new User(2, 'Jane', 'Smith', 'janesmith@example.com'),
        ];
    }

    /**
     * Create a new User entity and add it to the store.
     *
     * @param string $firstName the user's first name
     * @param string $lastName  the user's last name
     * @param string $email     the user's email address
     *
     * @return User the newly created User entity
     */
    public function create(string $firstName, string $lastName, string $email): User
    {
        $id = count($this->store) + 1;

        $user = new User($id, $firstName, $lastName, $email);
        $this->store[$id] = $user;

        return $user;
    }

    /**
     * Delete a user from the store by ID.
     *
     * @param int $id the ID of the user to delete
     *
     * @return bool true if the user was deleted, false if not found
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
     * @return User[] a sequential array of User entities
     */
    public function findAll(): array
    {
        return array_values($this->store);
    }

    /**
     * Find a user by their ID.
     *
     * @param int $id the ID of the user to find
     *
     * @return User|null the User entity if found, otherwise null
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
     * @param int         $id        the ID of the user to update
     * @param string|null $firstName the new first name (optional)
     * @param string|null $lastName  the new last name (optional)
     * @param string|null $email     the new email (optional)
     *
     * @return User|null the updated User entity, or null if the user was not found
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
