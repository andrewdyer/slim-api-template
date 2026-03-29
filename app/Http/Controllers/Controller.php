<?php

namespace App\Http\Controllers;

use Psr\Log\LoggerInterface;
use Slim\Container;

abstract class Controller
{
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    protected function getContainer(): Container
    {
        return $this->container;
    }

    protected function getLogger(): LoggerInterface
    {
        return $this->getContainer()->get(LoggerInterface::class);
    }
}
