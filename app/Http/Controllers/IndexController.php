<?php

namespace App\Http\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

/**
 * Class IndexController
 */
readonly class IndexController
{
    /**
     * Inject the Twig view renderer
     *
     * @param Twig $twig The Twig view renderer instance.
     */
    public function __construct(private Twig $twig)
    {
    }

    /**
     * Handles the HTTP request and renders the index page.
     *
     * @param Request $request The incoming HTTP request.
     * @param Response $response The HTTP response to be returned.
     *
     * @return Response The HTTP response containing the rendered Twig template.
     */
    public function __invoke(Request $request, Response $response): Response
    {
        return $this->twig->render($response, 'index.html.twig', [
            'controllerName' => 'IndexController',
            'controllerLocation' => __DIR__ . '/IndexController.php',
        ]);
    }
}
