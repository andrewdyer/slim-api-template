<?php

namespace App\Http\Controllers;

use Monolog\Logger;
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

    protected function getLogger(): Logger
    {
        return $this->getContainer()->get('logger');
    }
}
