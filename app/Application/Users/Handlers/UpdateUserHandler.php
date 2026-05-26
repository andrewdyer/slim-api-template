<?php

declare(strict_types=1);

namespace App\Application\Users\Handlers;

use App\Application\Users\Commands\UpdateUserCommand;
use App\Application\Users\Exceptions\UserNotFoundException;
use App\Domain\User\User;
use App\Domain\User\UserRepository;

/**
 * Handles the update of an existing user.
 */
final readonly class UpdateUserHandler
{
    /**
     * Creates a new UpdateUserHandler with the required dependencies.
     *
     * @param UserRepository $userRepository The backing user persistence layer.
     */
    public function __construct(private UserRepository $userRepository)
    {
    }

    /**
     * Handles the UpdateUserCommand and applies the changes to the existing user.
     *
     * @param  UpdateUserCommand     $command The update user command.
     * @return User                  The updated User entity.
     * @throws UserNotFoundException If no user exists with the given ID.
     */
    public function handle(UpdateUserCommand $command): User
    {
        $user = $this->userRepository->findById($command->id);

        if (null === $user) {
            throw new UserNotFoundException("User with ID {$command->id} not found.");
        }

        $updated = $this->userRepository->update(
            id: $user->getId(),
            firstName: $command->firstName,
            lastName: $command->lastName,
            email: $command->email,
        );

        if (null === $updated) {
            throw new UserNotFoundException("User with ID {$command->id} not found.");
        }

        return $updated;
    }
}
