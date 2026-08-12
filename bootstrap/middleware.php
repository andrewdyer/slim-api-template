<?php

declare(strict_types=1);

use Slim\App;

/**
 * Registers application middleware.
 */
return static function(App $app): void {
    // Add application middleware here.

    // Example:
    // $app->add(SomeMiddleware::class);

    // $app->add(function ($request, $handler) {
    //     $response = $handler->handle($request);
    //     return $response->withHeader('X-App', 'MyApp');
    // });
};
