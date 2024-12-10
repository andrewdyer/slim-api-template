<?php

use App\Http\Controllers\IndexController;
use DI\Container;

return function(Container $container) {
    $container->set(IndexController::class, function() {
        return new IndexController();
    });
};
