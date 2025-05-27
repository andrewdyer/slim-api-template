<?php

namespace App\Http\Actions;

use App\Http\Responders\TwigResponder;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

readonly class IndexAction
{
    public function __construct(private TwigResponder $responder)
    {
    }

    public function __invoke(Request $request, Response $response): Response
    {
        return $this->responder->respond($response, 'index.html.twig', [
            'actionName' => 'IndexAction',
            'actionLocation' => 'app/Http/Actions/IndexAction.php',
        ]);
    }
}
