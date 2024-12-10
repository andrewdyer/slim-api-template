<?php

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;

return function() {
    try {
        $dotenv = Dotenv::createImmutable(root_path('/'));
        $dotenv->load();
    } catch (InvalidPathException $ex) {
        exit($ex->getMessage());
    }
};
