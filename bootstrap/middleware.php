<?php

use Monolog\Logger;
use Slim\App;

return function(App $app) {
    $container = $app->getContainer();

    $logger = $container->get(Logger::class);
    $settings = $container->get('settings')['app'];

    $app->addErrorMiddleware(
        $settings['display_error_details'],
        $settings['log_errors'],
        $settings['log_error_details'],
        $logger
    );
};
