<?php

use App\Services\LoggerService;
use App\Services\TwigService;
use DI\Container;
use Monolog\Logger;
use Slim\Views\Twig;

return function(Container $container) {
    // Register the Logger service
    $container->set(Logger::class, function($container) {
        $settings = $container->get('settings')['logger'];

        return (new LoggerService($settings))();
    });

    // Register the Twig service
    $container->set(Twig::class, function($container) {
        $settings = $container->get('settings')['view'];

        return (new TwigService($settings))();
    });
};
