<?php

declare(strict_types=1);

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;

return function(?string $filename = '.env') {
    if (!get_env('APP_ENV')) {
        try {
            $dotenv = Dotenv::createImmutable(root_path('/'), $filename);
            $dotenv->load();
        } catch (InvalidPathException $ex) {
            exit($ex->getMessage());
        }
    }
};
