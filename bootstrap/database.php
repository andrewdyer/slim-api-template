<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Psr\Container\ContainerInterface;

return function(ContainerInterface $container) {
    $capsule = new Capsule();
    $capsule->addConnection($container->get('settings')['db']);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    return $capsule;
};
