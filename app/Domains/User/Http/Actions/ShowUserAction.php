<?php

declare(strict_types=1);

namespace App\Domains\User\Http\Actions;

use App\Domains\User\Repositories\UserRepository;
use App\Http\Responders\JsonResponder;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

readonly class ShowUserAction
{
    public function __construct(private JsonResponder $responder, private UserRepository $repository)
    {
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $userId = (int)$args['id'];

        $user = $this->repository->getUserById($userId);

        return $this->responder->respond($response, $user);
    }
}
