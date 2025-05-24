<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

readonly class UserController
{
    public function index(Request $request, Response $response): Response
    {
        $users = User::all();

        $response->getBody()->write(json_encode($users));

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function show(Request $request, Response $response, array $args): Response
    {
        $user = User::findOrFail((int)$args['id']);

        $response->getBody()->write(json_encode($user));

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function store(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        $user = new User();
        $user->first_name = Arr::get($data, 'first_name');
        $user->last_name = Arr::get($data, 'last_name');
        $user->save();

        $response->getBody()->write(json_encode($user));

        return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        $data = $request->getParsedBody();

        $user = User::findOrFail((int)$args['id']);

        if ($firstName = Arr::get($data, 'first_name')) {
            $user->first_name = $firstName;
        }

        if ($lastName = Arr::get($data, 'last_name')) {
            $user->last_name = $lastName;
        }

        $user->save();

        $response->getBody()->write(json_encode($user));

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function destroy(Request $request, Response $response, array $args): Response
    {
        $user = User::findOrFail((int)$args['id']);
        $user->delete();

        return $response->withStatus(204);
    }
}
