<?php

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;

return function() {
    try {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();
    } catch (InvalidPathException $ex) {
        exit($ex->getMessage());
    }
};