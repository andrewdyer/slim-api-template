<?php

namespace App\Http\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;

final class IndexController extends Controller
{
    public function __construct(protected readonly Twig $view)
    {
    }

    protected function handle(): Response
    {
        return $this->view->render($this->response, 'index/index.html.twig', [
            'controllerName' => 'IndexController',
            'controllerLocation' => __DIR__ . '/IndexController.php',
        ]);
    }
}
