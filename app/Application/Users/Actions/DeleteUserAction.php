<?php

declare(strict_types=1);

namespace App\Application\Users\Actions;

use AndrewDyer\Actions\Payloads\ActionPayload;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Handles deleting a user via HTTP.
 */
final class DeleteUserAction extends AbstractUserAction
{
    /**
     * Resolves the user ID from the route, deletes the user, and returns an empty response.
     *
     * @return Response A 204 JSON response with no body content.
     */
    protected function handle(): Response
    {
        $userId = (int)$this->resolveArg('id');

        $this->userService->delete($userId);

        $payload = ActionPayload::success(null, 204);

        return $this->respondWithJson($payload);
    }
}
