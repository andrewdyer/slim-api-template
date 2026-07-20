<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Application\DTOs\Input\CreateUserInput;
use App\Application\DTOs\Input\UpdateUserInput;
use App\Application\Exceptions\UserNotFoundException;
use App\Domain\Models\User;
use App\Domain\Repositories\UserRepositoryInterface;

/**
 * Handles user-related application logic, coordinating between actions and the repository.
 */
final readonly class UserService
{
    /**
     * Creates a new UserService with the required dependencies.
     *
     * @param UserRepositoryInterface $userRepository The backing user persistence layer.
     */
    public function __construct(
        protected UserRepositoryInterface $userRepository,
    ) {
    }

    /**
     * Returns all users from the repository.
     *
     * @return User[] An array of all User entities.
     */
    public function all(): array
    {
        return $this->userRepository->findAll();
    }

    /**
     * Creates a new user from the provided input and persists it via the repository.
     *
     * @param  CreateUserInput $input The data required to create the user.
     * @return User            The newly created User entity.
     */
    public function create(CreateUserInput $input): User
    {
        return $this->userRepository->create(
            $input->firstName,
            $input->lastName,
            $input->email
        );
    }

    /**
     * Deletes the user with the given ID from the repository.
     *
     * @param  int                   $id The unique identifier of the user to delete.
     * @throws UserNotFoundException If no user exists with the given ID.
     */
    public function delete(int $id): void
    {
        $user = $this->find($id);

        $this->userRepository->delete($user->getId());
    }

    /**
     * Returns the user with the given ID.
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
     * Returns a paginated list of users from the repository.
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
     * Updates an existing user with the fields provided in the input and returns the result.
     *
     * @param  UpdateUserInput       $input The data to apply to the existing user. Null fields are left unchanged.
     * @return User                  The updated User entity.
     * @throws UserNotFoundException If no user exists with the given ID.
     */
    public function update(UpdateUserInput $input): User
    {
        $user = $this->find($input->id);

        $updated = $this->userRepository->update(
            id: $user->getId(),
            firstName: $input->firstName,
            lastName: $input->lastName,
            email: $input->email
        );

        if (null === $updated) {
            throw new UserNotFoundException("User with ID {$input->id} not found.");
        }

        return $updated;
    }
}
