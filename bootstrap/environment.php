<?php

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
