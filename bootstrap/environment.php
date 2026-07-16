<?php

declare(strict_types=1);

use Dotenv\Dotenv;

/**
 * Loads the application environment configuration.
 */
return static function(): void {
    $environment = get_env('APP_ENV');

    if ($environment === 'testing') {
        Dotenv::createImmutable(root_path('/'), '.env.test')->load();

        return;
    }

    if (!$environment) {
        Dotenv::createImmutable(root_path('/'), '.env')->load();
    }
};
