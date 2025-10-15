<?php

namespace App\Application\Http\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;

final class ListUsersAction extends UserAction
{
    protected function action(): Response
    {
        $users = $this->userService->all();

        return $this->jsonResponder->respond($this->response, $users);
    }
}
