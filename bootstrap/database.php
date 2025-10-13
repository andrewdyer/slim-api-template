<?php

use DI\Container;
use Illuminate\Database\Capsule\Manager as Capsule;

return function (Container $container) {
    $connection = $container->get('settings')['database'];

    $capsule = new Capsule();
    $capsule->addConnection($connection);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    return $capsule;
};
