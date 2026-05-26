<?php

declare(strict_types=1);

namespace App\Application\Users\Handlers;

use App\Application\Users\Commands\DeleteUserCommand;
use App\Application\Users\Exceptions\UserNotFoundException;
use App\Domain\User\UserRepository;

/**
 * Handles the deletion of an existing user.
 */
final readonly class DeleteUserHandler
{
    /**
     * Creates a new DeleteUserHandler with the required dependencies.
     *
     * @param UserRepository $userRepository The backing user persistence layer.
     */
    public function __construct(private UserRepository $userRepository)
    {
    }

    /**
     * Handles the DeleteUserCommand and removes the user from the repository.
     *
     * @param  DeleteUserCommand     $command The delete user command.
     * @throws UserNotFoundException If no user exists with the given ID.
     */
    public function handle(DeleteUserCommand $command): void
    {
        $user = $this->userRepository->findById($command->id);

        if (null === $user) {
            throw new UserNotFoundException("User with ID {$command->id} not found.");
        }

        $this->userRepository->delete($user->getId());
    }
}
