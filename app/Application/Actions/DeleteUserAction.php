<?php

declare(strict_types=1);

namespace App\Application\Actions;

use App\Application\Exceptions\UserNotFoundException;
use JsonException;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Handles deleting a user via HTTP.
 */
final class DeleteUserAction extends AbstractUserAction
{
    /**
     * Handles the deletion of a user.
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
