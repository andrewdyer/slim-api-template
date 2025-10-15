<?php

namespace App\Application\Http\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;

final class DeleteUserAction extends UserAction
{
    protected function action(): Response
    {
        $userId = (int) $this->resolveArg('id');

        $this->userService->delete($userId);

        return $this->jsonResponder->respond($this->response, null, 204);
    }
}
