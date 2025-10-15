<?php

use DI\Container;
use Illuminate\Database\Capsule\Manager as Capsule;

return function (Container $container) {
    $settings = $container->get('settings');

    if (!isset($settings['database'])) {
        throw new InvalidArgumentException("Missing 'database' configuration in settings.");
    }

    $capsule = new Capsule();
    $capsule->addConnection($settings['database']);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    return $capsule;
};
