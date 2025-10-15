<?php

namespace App\Application\Http\Actions\User;

use App\Application\Users\DTO\CreateUserDTO;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * HTTP action for creating a new user.
 *
 * This action handles POST requests to create new user entities in the system.
 * It validates the request data through a DTO and delegates the creation
 * logic to the UserService.
 */
final class CreateUserAction extends UserAction
{
    /**
     * Handle the user creation request.
     *
     * This method processes the HTTP request to create a new user. It extracts
     * the user data from the request body, validates it through a DTO, creates
     * the user via the service layer, and returns the created user data with
     * a 201 status code.
     *
     * @return Response JSON response containing the created user data with 201 status
     */
    protected function action(): Response
    {
        $dto = CreateUserDTO::fromArray($this->getParsedBody());

        $createdUser = $this->userService->create($dto);

        return $this->jsonResponder->respond($this->response, $createdUser, 201);
    }
}
