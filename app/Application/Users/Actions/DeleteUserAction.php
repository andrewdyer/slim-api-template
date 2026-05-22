<?php

declare(strict_types=1);

namespace App\Application\Users\Actions;

use App\Application\Users\Exceptions\UserNotFoundException;
use JsonException;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Handles deleting a user via HTTP.
 */
final class DeleteUserAction extends AbstractUserAction
{
    /**
     * Resolves the user ID from the route, deletes the user, and returns an empty response.
     *
     * @return Response              A 204 JSON response with no body content.
     * @throws UserNotFoundException If no user exists with the given ID.
     * @throws JsonException         If the request body contains invalid JSON.
     */
    protected function handle(): Response
    {
        $userId = (int)$this->resolveArg('id');

        $this->userService->delete($userId);

        return $this->ok(null, null, 204);
    }
}
