<?php

return [
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/database/migrations',
        'seeds'      => '%%PHINX_CONFIG_DIR%%/database/seeds',
    ],
    'environments' => [
        'default_migration_table' => 'migrations',
        'default_environment'     => 'default',
        'default'                 => [
            'adapter' => 'mysql',
            'host'    => '127.0.0.1',
            'name'    => 'development_db',
            'user'    => 'root',
            'pass'    => 'password',
            'port'    => '3306',
            'charset' => 'utf8mb4',
        ],
    ],
    'version_order'        => 'creation',
    'migration_base_class' => 'Database\Migrations\AbstractMigration\AbstractMigration',
];
