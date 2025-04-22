<?php

namespace App\Services;

use Slim\Views\Twig;

readonly class TwigService
{
    public function __construct(private array $settings = [])
    {
    }

    public function __invoke(): Twig
    {
        return Twig::create(root_path('/resources/views'), ['cache' => $this->settings['cache']]);
    }
}
