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
     * @param UserRepository $userRepository the repository used to persist and retrieve users
     */
    public function __construct(
        protected UserRepository $userRepository,
    ) {
    }

    /**
     * Create a new user in the system.
     *
     * This method receives a Data Transfer Object (DTO) that encapsulates
     * validated input data and delegates persistence to the repository layer.
     *
     * @param CreateUserDTO $dto the data required to create a user
     *
     * @return User the newly created user entity
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
     * Find a user by their ID.
     *
     * This method retrieves a user from the repository using their unique
     * identifier. If the user cannot be found, it throws an exception
     * rather than returning null, ensuring consistent error handling.
     *
     * @param int $id the unique identifier of the user to find
     *
     * @return User the user entity if found
     *
     * @throws UserNotFoundException if no user exists with the given ID
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
     * @param UpdateUserDTO $dto the data containing user ID and fields to update
     *
     * @return User the updated user entity
     *
     * @throws UserNotFoundException if no user exists with the given ID
     */
    public function update(UpdateUserDTO $dto): User
    {
        $user = $this->find($dto->id);

        return $this->userRepository->update(id: $user->getId(),
            firstName: $dto->firstName ?? $user->getFirstName(),
            lastName: $dto->lastName ?? $user->getLastName(),
            email: $dto->email ?? $user->getEmail());
    }
}
