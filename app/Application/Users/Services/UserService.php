<?php

namespace App\Application\Users\Services;

use App\Application\Users\DTO\CreateUserDTO;
use App\Application\Users\DTO\UpdateUserDTO;
use App\Application\Users\Exceptions\UserNotFoundException;
use App\Domain\Users\Entities\User;
use App\Domain\Users\Repositories\UserRepository;

/**
 * Service responsible for managing user-related operations.
 *
 * This service coordinates application logic involving users, such as
 * creation, updates, and retrieval. It acts as a boundary between
 * the HTTP or console layer and the domain repositories.
 */
final readonly class UserService
{
    /**
     * Create a new UserService instance.
     *
     * @param UserRepository $userRepository The repository used to persist and retrieve users
     */
    public function __construct(
        protected UserRepository $userRepository,
    ) {
    }

    /**
     * Retrieve all users from the system.
     *
     * This method fetches and returns all user entities currently stored
     * in the repository. If no users exist, an empty array is returned.
     *
     * @return User[] An array of all user entities in the system
     */
    public function all(): array
    {
        return $this->userRepository->findAll();
    }

    /**
     * Create a new user in the system.
     *
     * This method receives a Data Transfer Object (DTO) that encapsulates
     * validated input data and delegates persistence to the repository layer.
     *
     * @param CreateUserDTO $dto The data required to create a user
     *
     * @return User The newly created user entity
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
     * Delete a user from the system.
     *
     * This method attempts to remove a user with the specified ID from
     * the repository. The operation returns a boolean indicating whether
     * the deletion was successful or not.
     *
     * @param int $id The unique identifier of the user to delete
     *
     * @return bool True if the user was successfully deleted, false if the user was not found
     */
    public function delete(int $id): bool
    {
        return $this->userRepository->delete($id);
    }

    /**
     * Find a user by their ID.
     *
     * This method retrieves a user from the repository using their unique
     * identifier. If the user cannot be found, it throws an exception
     * rather than returning null, ensuring consistent error handling.
     *
     * @param int $id The unique identifier of the user to find
     *
     * @return User The user entity if found
     *
     * @throws UserNotFoundException If no user exists with the given ID
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
     * Update an existing user in the system.
     *
     * This method receives a Data Transfer Object (DTO) containing the user ID
     * and optional updated field values. Only non-null fields in the DTO will
     * be updated, allowing for partial updates. The method first verifies the
     * user exists before attempting the update operation.
     *
     * @param UpdateUserDTO $dto The data containing user ID and fields to update
     *
     * @return User The updated user entity
     *
     * @throws UserNotFoundException If no user exists with the given ID
     */
    public function update(UpdateUserDTO $dto): User
    {
        $user = $this->find($dto->id);

        return $this->userRepository->update(
            id: $user->getId(),
            firstName: $dto->firstName,
            lastName: $dto->lastName,
            email: $dto->email
        );
    }
}
