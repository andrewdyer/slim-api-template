<?php

namespace App\Application\Users\Services;

use App\Application\Users\DTO\CreateUserDTO;
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
}
