<?php

declare(strict_types=1);

use AndrewDyer\Settings\Contracts\SettingsInterface;
use Slim\App;

return function(App $app): void {
    $app->addBodyParsingMiddleware();

    $app->addRoutingMiddleware();

    $container = $app->getContainer();

    $settings = $container->get(SettingsInterface::class);

    $app->addErrorMiddleware(
        $settings->get('displayErrorDetails'),
        $settings->get('logError'),
        $settings->get('logErrorDetails')
    );
};
