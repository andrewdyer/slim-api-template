<?php

declare(strict_types=1);

namespace App\Domains\User\Http\Actions;

use App\Domains\User\Repositories\UserRepository;
use App\Infrastructure\Http\Responders\JsonResponder;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

readonly class CreateUserAction
{
    public function __construct(private JsonResponder $responder, private UserRepository $repository)
    {
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        $user = $this->repository->createUser($data);

        return $this->responder->respond($response, $user, 201);
    }
}
