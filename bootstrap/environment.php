<?php

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;

/**
 * Load environment variables from .env file if not already set.
 *
 * Only reads from file if variables aren't already defined by server
 * configuration, allowing flexible deployment options.
 *
 * @param string|null $filename The environment file name (default: '.env')
 * @return void
 * @throws InvalidPathException If the environment file is invalid or inaccessible
 */
return function(?string $filename = '.env'): void {
    if (!get_env('APP_ENV')) {
        try {
            $dotenv = Dotenv::createImmutable(base_path('/'), $filename);
            $dotenv->load();
        } catch (InvalidPathException $ex) {
            exit($ex->getMessage());
        }
    }
};
