<?php

use App\Http\Actions\IndexAction;
use Slim\App;

return function(App $app) {
    $app->get('/', IndexAction::class);
};
