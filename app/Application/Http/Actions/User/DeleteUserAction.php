<?php

declare(strict_types=1);

namespace App\Application\Http\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;

/**
 * HTTP action for deleting a user.
 *
 * This action handles DELETE requests to remove user entities from the system.
 * It extracts the user ID from the route parameters and delegates the deletion
 * to the UserService.
 */
final class DeleteUserAction extends UserAction
{
    /**
     * Handle the user deletion request.
     *
     * This method processes the HTTP request to delete a user. It extracts
     * the user ID from the route parameters, performs the deletion through
     * the service layer, and returns an empty response with a 204 status code
     * to indicate successful deletion.
     *
     * @return Response Empty JSON response with 204 status code
     */
    protected function action(): Response
    {
        $userId = (int) $this->resolveArg('id');

        $this->userService->delete($userId);

        return $this->jsonResponder->respond($this->response, null, 204);
    }
}
