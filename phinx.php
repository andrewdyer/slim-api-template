@@ -0,0 +1,28 @@
<?php

require_once __DIR__ . '/vendor/autoload.php';

require_from_root('bootstrap/environment.php')();

return [
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/database/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/database/seeds',
    ],
    'templates' => [
        'style' => 'up_down',
    ],
    'environments' => [
        'default_migration_table' => 'migrations',
        'default_environment' => 'default',
        'default' => [
            'adapter' => get_env('DB_DRIVER'),
            'host' => get_env('DB_HOST'),
            'port' => get_env('DB_PORT'),
            'name' => get_env('DB_DATABASE'),
            'user' => get_env('DB_USERNAME'),
            'pass' => get_env('DB_PASSWORD'),
            'charset' => get_env('DB_CHARSET'),
        ],
    ],
    'version_order' => 'creation',
    'migration_base_class' => Database\Migrations\AbstractMigration::class,
];
