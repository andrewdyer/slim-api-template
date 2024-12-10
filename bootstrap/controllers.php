<?php

use App\Http\Controllers\IndexController;
use Psr\Container\ContainerInterface;

return function(ContainerInterface $container) {
    $container->set(IndexController::class, function() {
        return new IndexController();
    });
};
