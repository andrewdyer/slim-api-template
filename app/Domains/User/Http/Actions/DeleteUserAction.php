<?php

declare(strict_types=1);

namespace App\Domains\User\Http\Actions;

use App\Domains\User\Services\UserService;
use App\Http\Responders\JsonResponder;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

readonly class DeleteUserAction
{
    public function __construct(private JsonResponder $responder, private UserService $userService)
    {
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $userId = (int)$args['id'];

        $this->userService->delete($userId);

        return $this->responder->respond($response, null, 204);
    }
}
