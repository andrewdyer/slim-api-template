<?php

use App\Services\LoggerService;
use DI\Container;
use Monolog\Logger;
use Slim\Views\Twig;

return function(Container $container) {
    // Register the Logger service
    $container->set(Logger::class, function($container) {
        $settings = $container->get('settings')['logger'];

        return (new LoggerService($settings))();
    });

    $container->set(Twig::class, function($container) {
        $settings = $container->get('settings')['view'];

        return Twig::create(root_path('/resources/views'), ['cache' => $settings['cache']]);
    });
};
