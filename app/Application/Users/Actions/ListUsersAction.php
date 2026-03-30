<?php

declare(strict_types=1);

namespace App\Application\Users\Actions;

use AndrewDyer\Actions\Payloads\ActionPayload;
use App\Application\Users\DTOs\UserResponseDTO;
use App\Domain\User\User;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Handles listing all users via HTTP.
 */
final class ListUsersAction extends AbstractUserAction
{
    /**
     * Retrieves all users from the service and returns them as a JSON collection.
     *
     * @return Response A 200 JSON response containing an array of all users.
     */
    protected function handle(): Response
    {
        $users = $this->userService->all();

        $responseData = array_map(
            fn (User $user) => UserResponseDTO::fromDomain($user),
            $users
        );

        $payload = ActionPayload::success($responseData);

        return $this->respondWithJson($payload);
    }
}
