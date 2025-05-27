<?php

declare(strict_types=1);

namespace App\Http\Responders;

use Psr\Http\Message\ResponseInterface;
use Slim\Views\Twig;

readonly class TwigResponder
{
    public function __construct(private Twig $twig)
    {
    }

    public function respond(
        ResponseInterface $response,
        string $template,
        array $data = [],
        int $status = 200
    ): ResponseInterface {
        return $this->twig->render($response->withStatus($status), $template, $data);
    }
}
