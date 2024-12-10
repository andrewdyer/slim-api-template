<?php

namespace App\Http\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class IndexController
{
    public function __invoke(Request $request, Response $response): Response
    {
        $response->getBody()->write('Hello, World!');

        return $response;
    }
}
