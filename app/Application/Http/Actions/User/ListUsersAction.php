<?php

namespace App\Application\Http\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;

/**
 * HTTP action for retrieving all users.
 *
 * This action handles GET requests to retrieve a list of all users in the
 * system. It delegates to the UserService to fetch the data and returns
 * it as a JSON response.
 */
final class ListUsersAction extends UserAction
{
    /**
     * Handle the request to list all users.
     *
     * This method retrieves all users from the system through the UserService
     * and returns them as a JSON response with a 200 status code.
     *
     * @return Response JSON response containing an array of all users
     */
    protected function action(): Response
    {
        $users = $this->userService->all();

        return $this->jsonResponder->respond($this->response, $users);
    }
}
