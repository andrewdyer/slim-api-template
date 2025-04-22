<?php

/**
 * Loads environment variables from a `.env` file into the application.
 *
 * Environment variables are used to configure key parts of the application,
 * such as database connections, logging, and external services.
 *
 * The `.env` file is only loaded when the application environment `APP_ENV`
 * is set to 'local' or is not defined. In all other environments, it's assumed
 * that environment variables are already set at the system level.
 */

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;

return function() {
    if (!get_env('APP_ENV') || get_env('APP_ENV') === 'local') {
        try {
            $dotenv = Dotenv::createImmutable(root_path('/'));
            $dotenv->load();
        } catch (InvalidPathException $ex) {
            exit($ex->getMessage());
        }
    }
};
