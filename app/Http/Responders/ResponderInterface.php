<?php

declare(strict_types=1);

namespace App\Http\Responders;

use Psr\Http\Message\ResponseInterface;

interface ResponderInterface
{
    public function respond(ResponseInterface $response, mixed $data, int $status = 200): ResponseInterface;
}
