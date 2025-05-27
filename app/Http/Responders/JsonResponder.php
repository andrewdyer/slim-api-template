<?php

declare(strict_types=1);

namespace App\Http\Responders;

use Psr\Http\Message\ResponseInterface;

class JsonResponder implements ResponderInterface
{
    #[\Override]
    public function respond(ResponseInterface $response, mixed $data, int $status = 200): ResponseInterface
    {
        $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }
}
