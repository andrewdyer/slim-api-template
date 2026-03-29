<?php

declare(strict_types=1);

use App\Application\Http\Actions\IndexAction;
use Slim\App;

return function (App $app): void {
    $app->get('/', IndexAction::class)->setName('index');
};