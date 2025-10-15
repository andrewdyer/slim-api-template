<?php

namespace App\Application\Http\Actions\User;

use App\Application\Users\DTO\UpdateUserDTO;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * HTTP action for updating an existing user.
 *
 * This action handles PUT/PATCH requests to update user entities in the system.
 * It extracts the user ID from the route and the update data from the request
 * body, then processes the update through the UserService.
 */
final class UpdateUserAction extends UserAction
{
    /**
     * Handle the user update request.
     *
     * This method processes the HTTP request to update an existing user. It
     * extracts the user ID from the route parameters and the update data from
     * the request body, validates the data through a DTO, performs the update
     * via the service layer, and returns the updated user data.
     *
     * @return Response JSON response containing the updated user data
     *
     * @throws UserNotFoundException If the user with the specified ID doesn't exist
     */
    protected function action(): Response
    {
        $userId = (int) $this->resolveArg('id');

        $dto = UpdateUserDTO::fromArray($userId, $this->getParsedBody());

        $updatedUser = $this->userService->update($dto);

        return $this->jsonResponder->respond($this->response, $updatedUser);
    }
}
