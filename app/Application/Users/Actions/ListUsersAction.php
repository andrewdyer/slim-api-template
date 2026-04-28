<?php

declare(strict_types=1);

namespace App\Application\Users\Actions;

use App\Application\Users\DTOs\UserResponseDTO;
use App\Domain\User\User;
use JsonException;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Handles listing all users via HTTP.
 */
final class ListUsersAction extends AbstractUserAction
{
    /**
     * Retrieves all users from the service and returns them as a JSON collection.
     *
     * @return Response      A 200 JSON response containing an array of all users.
     * @throws JsonException If the request body contains invalid JSON.
     */
    protected function handle(): Response
    {
        $users = $this->userService->all();

        $responseData = array_map(
            fn (User $user) => UserResponseDTO::fromDomain($user),
            $users
        );

        return $this->ok($responseData);
    }
}
