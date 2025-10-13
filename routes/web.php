<?php

use Slim\App;
use App\Http\Controllers\IndexController;

return function (App $app) {
    $app->get('/', IndexController::class)->setName('index');
};