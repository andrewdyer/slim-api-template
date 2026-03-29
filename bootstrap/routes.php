<?php

use App\Application\Http\Actions\IndexAction;
use Slim\App;

return function (App $app) {
    $app->get('/', IndexAction::class)->setName('index');
};