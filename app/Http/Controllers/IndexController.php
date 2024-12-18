<?php

namespace App\Http\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class IndexController
{
    private Twig $twig;

    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        return $this->twig->render($response, 'index.html.twig', [
            'controllerName' => 'IndexController',
            'controllerLocation' => __DIR__ . '/IndexController.php',
        ]);
    }
}
