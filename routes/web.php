<?php

use App\Http\Controllers\IndexController;
use Slim\App;

return function(App $app) {
    $app->get('/', IndexController::class);
};
