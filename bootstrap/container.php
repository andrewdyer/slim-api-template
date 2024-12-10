<?php

use DI\Container;

return function() {
    $container = new Container();

    $container->set('settings', function() {
        return [
            'db' => [
                'driver'    => 'mysql',
                'host'      => '127.0.0.1',
                'port'      => 3306,
                'database'  => 'development_db',
                'username'  => 'root',
                'password'  => 'password',
                'charset'   => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix'    => '',
            ],
        ];
    });

    return $container;
};
