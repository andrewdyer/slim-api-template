<?php

declare(strict_types=1);

use Dotenv\Dotenv;

require __DIR__ . '/vendor/autoload.php';

if (!get_env('APP_ENV')) {
    Dotenv::createImmutable(root_path('/'))->load();
}

return [
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/database/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/database/seeds',
    ],
    'environments' => [
        'default_migration_table' => 'migrations',
        'default_environment' => 'default',
        'default' => [
            'adapter' => get_env('DB_DRIVER'),
            'host' => get_env('DB_HOST'),
            'name' => get_env('DB_DATABASE'),
            'user' => get_env('DB_USERNAME'),
            'pass' => get_env('DB_PASSWORD'),
            'port' => get_env('DB_PORT'),
            'charset' => get_env('DB_CHARSET'),
        ],
    ],
    'version_order' => 'creation',
];
