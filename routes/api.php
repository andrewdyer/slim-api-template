<?php

declare(strict_types=1);

use App\Http\Controllers\Api\UserController;
use Slim\App;

return function(App $app) {
    $app->group('/api', function($api) {
        $api->group('/v1', function($v1) {
            $v1->group('/users', function($users) {
                $users->get('', [UserController::class, 'index']);
                $users->post('', [UserController::class, 'store']);
                $users->group('/{id}', function($user) {
                    $user->get('', [UserController::class, 'show']);
                    $user->put('', [UserController::class, 'update']);
                    $user->delete('', [UserController::class, 'destroy']);
                });
            });
        });
    });
};
