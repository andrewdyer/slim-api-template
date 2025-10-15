<?php

namespace App\Application\Http\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;

final class ShowUserAction extends UserAction
{
    protected function action(): Response
    {
        $userId = (int) $this->resolveArg('id');

        $user = $this->userService->find($userId);

        return $this->jsonResponder->respond($this->response, $user);
    }
}
