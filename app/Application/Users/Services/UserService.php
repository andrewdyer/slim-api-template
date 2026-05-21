<?php

declare(strict_types=1);

namespace App\Application\Users\Services;

use App\Application\Users\DTOs\CreateUserDTO;
use App\Application\Users\DTOs\UpdateUserDTO;
use App\Application\Users\Exceptions\UserNotFoundException;
use App\Domain\User\User;
use App\Domain\User\UserRepository;

/**
 * Handles user-related application logic, coordinating between actions and the repository.
 *
 * Acts as the primary entry point for all user use cases in the application layer.
 */
final readonly class UserService
{
    /**
     * Injects the repository used to persist and retrieve user entities.
     *
     * @param UserRepository $userRepository The backing user persistence layer.
     */
    public function __construct(
        protected UserRepository $userRepository,
    ) {
    }

    /**
     * Retrieves all users from the repository.
     *
     * @return User[] An array of all User entities.
     */
    public function all(): array
    {
        return $this->userRepository->findAll();
    }

    /**
     * Creates a new user from the provided DTO and persists it via the repository.
     *
     * @param  CreateUserDTO $dto The data required to create the user.
     * @return User          The newly created User entity.
     */
    public function create(CreateUserDTO $dto): User
    {
        return $this->userRepository->create(
            $dto->firstName,
            $dto->lastName,
            $dto->email
        );
    }

    /**
     * Deletes the user with the given ID from the repository.
     *
     * @param  int  $id The unique identifier of the user to delete.
     * @return bool True if the user was deleted, false if no user with that ID existed.
     */
    public function delete(int $id): bool
    {
        return $this->userRepository->delete($id);
    }

    /**
     * Finds and returns the user with the given ID.
     *
     * @param  int                   $id The unique identifier of the user to retrieve.
     * @return User                  The matching User entity.
     * @throws UserNotFoundException If no user exists with the given ID.
     */
    public function find(int $id): User
    {
        $user = $this->userRepository->findById($id);

        if (null === $user) {
            throw new UserNotFoundException("User with ID {$id} not found.");
        }

        return $user;
    }

    /**
     * Retrieves a paginated list of users from the repository.
     *
     * @param  int                              $page    The 1-indexed page number. Must be >= 1.
     * @param  int                              $perPage The number of users per page. Must be >= 1.
     * @return array{users: User[], total: int} An array containing the users for the requested page and the total count.
     */
    public function paginated(int $page, int $perPage): array
    {
        return $this->userRepository->findPaginated($page, $perPage);
    }

    /**
     * Updates an existing user with the fields provided in the DTO and returns the result.
     *
     * @param  UpdateUserDTO         $dto The data to apply to the existing user. Null fields are left unchanged.
     * @return User                  The updated User entity.
     * @throws UserNotFoundException If no user exists with the given ID.
     */
    public function update(UpdateUserDTO $dto): User
    {
        $user = $this->find($dto->id);

        $updated = $this->userRepository->update(
            id: $user->getId(),
            firstName: $dto->firstName,
            lastName: $dto->lastName,
            email: $dto->email
        );

        if (null === $updated) {
            throw new UserNotFoundException("User with ID {$dto->id} not found.");
        }

        return $updated;
    }
}
