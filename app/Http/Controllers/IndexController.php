<?php

namespace App\Http\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

readonly class IndexController
{
    public function __construct(private Twig $twig)
    {
    }

    public function __invoke(Request $request, Response $response): Response
    {
        return $this->twig->render($response, 'index.html.twig', [
            'controllerName' => 'IndexController',
            'controllerLocation' => __DIR__ . '/IndexController.php',
        ]);
    }
}
