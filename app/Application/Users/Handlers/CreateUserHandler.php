<?php

declare(strict_types=1);

namespace App\Application\Users\Handlers;

use App\Application\Users\Commands\CreateUserCommand;
use App\Domain\User\User;
use App\Domain\User\UserRepository;

/**
 * Handles the creation of a new user.
 */
final readonly class CreateUserHandler
{
    /**
     * Creates a new CreateUserHandler with the required dependencies.
     *
     * @param UserRepository $userRepository The backing user persistence layer.
     */
    public function __construct(private UserRepository $userRepository)
    {
    }

    /**
     * Handles the CreateUserCommand and persists the new user.
     *
     * @param  CreateUserCommand $command The create user command.
     * @return User              The newly created User entity.
     */
    public function handle(CreateUserCommand $command): User
    {
        return $this->userRepository->create(
            $command->firstName,
            $command->lastName,
            $command->email,
        );
    }
}
