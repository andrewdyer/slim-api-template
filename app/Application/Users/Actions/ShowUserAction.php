<?php

declare(strict_types=1);

namespace App\Application\Users\Actions;

use App\Application\Users\DTOs\UserResponseDTO;
use App\Application\Users\Exceptions\UserNotFoundException;
use JsonException;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Handles retrieving a single user by ID via HTTP.
 */
final class ShowUserAction extends AbstractUserAction
{
    /**
     * Resolves the user ID from the route, fetches the matching user, and returns it as JSON.
     *
     * @return Response              A 200 JSON response containing the requested user.
     * @throws UserNotFoundException If no user exists with the given ID.
     * @throws JsonException         If the request body contains invalid JSON.
     */
    protected function handle(): Response
    {
        $userId = (int)$this->resolveArg('id');

        $user = $this->userService->find($userId);

        $responseDto = UserResponseDTO::fromDomain($user);

        return $this->ok($responseDto);
    }
}
