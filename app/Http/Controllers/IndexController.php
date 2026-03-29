<?php

namespace App\Http\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;

class IndexController
{
    public function index(Request $request, Response $response): Response
    {
        $response->getBody()->write('Hello, world!');

        return $response;
    }
}
