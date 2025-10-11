<?php

declare(strict_types=1);

namespace App\Http\Actions;

use App\Domain\User\Services\UserService;
use App\Http\Responders\JsonResponder;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

readonly class ListUsersAction
{
    public function __construct(private JsonResponder $responder, private UserService $userService)
    {
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $user = $this->userService->getAll();

        return $this->responder->respond($response, $user);
    }
}
