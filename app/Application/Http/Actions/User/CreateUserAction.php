<?php

namespace App\Application\Http\Actions\User;

use App\Application\Users\DTO\CreateUserDTO;
use Psr\Http\Message\ResponseInterface as Response;

final class CreateUserAction extends UserAction
{
    protected function action(): Response
    {
        $dto = CreateUserDTO::fromArray($this->getParsedBody());

        $createdUser = $this->userService->create($dto);

        return $this->jsonResponder->respond($this->response, $createdUser, 201);
    }
}
