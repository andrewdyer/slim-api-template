<?php

use Slim\App;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

/**
 * Register application-specific middleware.
 *
 * Configures custom middleware that executes before the core
 * Slim framework middleware in the request pipeline.
 *
 * @param App $app The Slim application instance
 * @return void
 */
return function(App $app) {
    $container = $app->getContainer();

    $app->add(TwigMiddleware::create($app, $container->get(Twig::class)));
};
