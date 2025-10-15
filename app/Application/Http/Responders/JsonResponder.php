<?php

namespace App\Application\Http\Responders;

use Psr\Http\Message\ResponseInterface as Response;

final class JsonResponder
{
    public function respond(Response $response, mixed $data, int $status = 200): Response
    {
        $payload = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json;charset=utf-8')
            ->withStatus($status);
    }
}
