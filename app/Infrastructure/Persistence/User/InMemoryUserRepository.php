<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\User;

use App\Domain\User\User;
use App\Domain\User\UserRepository;

/**
 * In-memory implementation of UserRepository, pre-seeded with sample data.
 *
 * Intended for development and testing purposes. Data does not persist between requests.
 */
final class InMemoryUserRepository implements UserRepository
{
    /**
     * @var int Counter used to assign a unique, auto-incrementing ID to each new user.
     */
    private int $nextId = 1;

    /**
     * @var array<int, User> The in-memory store of User entities, keyed by user ID.
     */
    private array $store = [];

    /**
     * Seeds the repository with a predefined set of sample users.
     */
    public function __construct()
    {
        $this->create('Oliver', 'French', 'oliver.french@example.com');
        $this->create('Charlotte', 'Anderson', 'charlotte.anderson@example.com');
        $this->create('Henry', 'Thomas', 'henry.thomas@example.com');
        $this->create('Amelia', 'Moore', 'amelia.moore@example.com');
        $this->create('Lucas', 'Martin', 'lucas.martin@example.com');
    }

    /**
     * Creates a new User entity, assigns it the next available ID, and stores it.
     *
     * @param  string $firstName The user's first name.
     * @param  string $lastName  The user's last name.
     * @param  string $email     The user's email address.
     * @return User   The newly created and stored User entity.
     */
    public function create(string $firstName, string $lastName, string $email): User
    {
        $user = new User($this->nextId++, $firstName, $lastName, $email);
        $this->store[$user->getId()] = $user;

        return $user;
    }

    /**
     * Removes the user with the given ID from the in-memory store.
     *
     * @param int $id The unique identifier of the user to delete.
     */
    public function delete(int $id): void
    {
        unset($this->store[$id]);
    }

    /**
     * Returns all users currently held in the in-memory store.
     *
     * @return User[] An indexed array of all stored User entities.
     */
    public function findAll(): array
    {
        return array_values($this->store);
    }

    /**
     * Returns a paginated subset of users from the in-memory store.
     *
     * @param  int                              $page    The 1-indexed page number. Must be >= 1.
     * @param  int                              $perPage The number of users per page. Must be >= 1.
     * @return array{users: User[], total: int} An array containing the users for the requested page and the total count.
     */
    public function findPaginated(int $page, int $perPage): array
    {
        $allUsers = $this->findAll();
        $total = count($allUsers);
        $offset = ($page - 1) * $perPage;

        $users = array_slice($allUsers, $offset, $perPage);

        return [
            'users' => $users,
            'total' => $total,
        ];
    }

    /**
     * Looks up a user by their ID in the in-memory store.
     *
     * @param  int       $id The unique identifier of the user to retrieve.
     * @return User|null The matching User entity, or null if not found.
     */
    public function findById(int $id): ?User
    {
        return $this->store[$id] ?? null;
    }

    /**
     * Replaces the stored user with a new User entity reflecting the applied changes.
     *
     * @param  int         $id        The unique identifier of the user to update.
     * @param  string|null $firstName The new first name, or null to retain the existing value.
     * @param  string|null $lastName  The new last name, or null to retain the existing value.
     * @param  string|null $email     The new email address, or null to retain the existing value.
     * @return User|null   The updated User entity, or null if no user with that ID existed.
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
