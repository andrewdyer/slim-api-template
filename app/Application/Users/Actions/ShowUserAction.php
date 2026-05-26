<?php

declare(strict_types=1);

namespace App\Application\Users\Actions;

use AndrewDyer\Actions\AbstractAction;
use App\Application\Users\DTOs\UserResponseDTO;
use App\Application\Users\Exceptions\UserNotFoundException;
use App\Domain\User\UserRepository;
use JsonException;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Handles retrieving a single user by ID via HTTP.
 */
final class ShowUserAction extends AbstractAction
{
    /**
     * Creates a new ShowUserAction.
     *
     * @param UserRepository $userRepository The repository used to retrieve users.
     */
    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    /**
     * Handles the retrieval of a single user by ID.
     *
     * @return Response              A 200 JSON response containing the requested user.
     * @throws UserNotFoundException If no user exists with the given ID.
     * @throws JsonException         If the request body contains invalid JSON.
     */
    protected function handle(): Response
    {
        $userId = (int)$this->resolveArg('id');

        $user = $this->userRepository->findById($userId);

        if (null === $user) {
            throw new UserNotFoundException("User with ID {$userId} not found.");
        }

        $responseDto = UserResponseDTO::fromDomain($user);

        return $this->ok($responseDto);
    }
}
