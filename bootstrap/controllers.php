<?php

use App\Http\Controllers\IndexController;
use DI\Container;
use Slim\Views\Twig;

return function(Container $container) {
    $container->set(IndexController::class, function() use ($container) {
        return new IndexController($container->get(Twig::class));
    });
};
