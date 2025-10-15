<?php

namespace App\Application\Http\Actions\User;

use App\Application\Users\DTO\UpdateUserDTO;
use Psr\Http\Message\ResponseInterface as Response;

final class UpdateUserAction extends UserAction
{
    protected function action(): Response
    {
        $userId = (int) $this->resolveArg('id');

        $dto = UpdateUserDTO::fromArray($userId, $this->getParsedBody());

        $updatedUser = $this->userService->update($dto);

        return $this->jsonResponder->respond($this->response, $updatedUser);
    }
}
