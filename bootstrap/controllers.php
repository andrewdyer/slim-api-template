<?php

use App\Http\Controllers\IndexController;
use DI\Container;
use Slim\Views\Twig;

/*
 * This file is used to register controller classes in the dependency 
 * injection container.
 *
 * Controllers are responsible for handling incoming HTTP requests 
 * and returning appropriate responses.
 *
 * Each controller should be defined in `src/app/Http/Controllers` 
 * and registered here with its required dependencies.
 *
 * To register a new controller:
 * $container->set(MyController::class, function () use ($container) {
 *     return new MyController($container->get(MyService::class));
 * });
 */
return function(Container $container) {
    $container->set(IndexController::class, function() use ($container) {
        return new IndexController($container->get(Twig::class));
    });
};
