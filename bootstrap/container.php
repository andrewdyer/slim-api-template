<?php

use DI\Container;

return function() {
    $container = new Container();

    $container->set('settings', function() {
        return [
            'db' => [
                'driver'    => get_env('DB_DRIVER'),
                'host'      => get_env('DB_HOST'),
                'port'      => get_env('DB_PORT'),
                'database'  => get_env('DB_DATABASE'),
                'username'  => get_env('DB_USERNAME'),
                'password'  => get_env('DB_PASSWORD'),
                'charset'   => get_env('DB_CHARSET'),
                'collation' => get_env('DB_COLLATION'),
                'prefix'    => get_env('DB_PREFIX'),
            ],
        ];
    });

    return $container;
};
