<?php

declare(strict_types=1);

use App\Domains\User\Http\Actions\CreateUserAction;
use App\Http\Actions\DeleteUserAction;
use App\Http\Actions\ListUsersAction;
use App\Http\Actions\ShowUserAction;
use App\Http\Actions\UpdateUserAction;
use Slim\App;

return function(App $app) {
    $app->group('/api', function($api) {
        $api->group('/v1', function($v1) {
            $v1->group('/users', function($users) {
                $users->get('', ListUsersAction::class);
                $users->post('', CreateUserAction::class);
                $users->group('/{id}', function($user) {
                    $user->delete('', DeleteUserAction::class);
                    $user->get('', ShowUserAction::class);
                    $user->put('', UpdateUserAction::class);
                });
            });
        });
    });
};
