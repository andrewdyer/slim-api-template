<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

readonly class UserController
{
    public function index(Request $request, Response $response): Response
    {
        $users = [['id' => 1, 'name' => 'Alice'], ['id' => 2, 'name' => 'Bob']];

        $response->getBody()->write(json_encode($users));

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function show(Request $request, Response $response, array $args): Response
    {
        $user = ['id' => $args['id'], 'name' => 'Alice'];

        $response->getBody()->write(json_encode($user));

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function store(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        // Save user logic...

        $response->getBody()->write(json_encode(['message' => 'User created']));

        return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        $data = $request->getParsedBody();

        // Update user logic...

        $response->getBody()->write(json_encode(['message' => 'User updated']));

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function destroy(Request $request, Response $response, array $args): Response
    {
        // Delete user logic...

        $response->getBody()->write(json_encode(['message' => 'User deleted']));

        return $response->withHeader('Content-Type', 'application/json');
    }
}
