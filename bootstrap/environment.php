<?php

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;

/**
 * Loads environment variables from a specified file into the application.
 *
 * Environment variables are used to configure key parts of the application,
 * such as database connections, logging, and external services.
 *
 * The environment file is loaded only when the application environment `APP_ENV`
 * is not set. In all other environments, it assumes environment variables are
 * already set externally (e.g., in production).
 *
 * @param string|null $filename The name of the environment file to load. Defaults to '.env'.
 *
 * @return void
 *
 * @throws InvalidPathException If the specified env file is not found.
 */
return function(?string $filename = '.env'): void {
    if (!get_env('APP_ENV')) {
        try {
            $dotenv = Dotenv::createImmutable(root_path('/'), $filename);
            $dotenv->load();
        } catch (InvalidPathException $ex) {
            exit($ex->getMessage());
        }
    }
};
