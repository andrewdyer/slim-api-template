<?php

declare(strict_types=1);

namespace App\Application\Http\Actions\User;

use App\Application\Users\Exceptions\UserNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * HTTP action for retrieving a single user by ID.
 *
 * This action handles GET requests to retrieve a specific user entity by
 * its unique identifier. The user ID is extracted from the route parameters.
 */
final class ShowUserAction extends UserAction
{
    /**
     * Handle the request to show a specific user.
     *
     * This method extracts the user ID from the route arguments, retrieves
     * the corresponding user through the UserService, and returns the user
     * data as a JSON response.
     *
     * @return Response JSON response containing the user data
     *
     * @throws UserNotFoundException If the user with the specified ID doesn't exist
     */
    protected function action(): Response
    {
        $userId = (int) $this->resolveArg('id');

        $user = $this->userService->find($userId);

        return $this->jsonResponder->respond($this->response, $user);
    }
}
