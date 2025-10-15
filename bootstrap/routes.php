<?php

use App\Application\Http\Actions\User\CreateUserAction;
use App\Application\Http\Actions\User\DeleteUserAction;
use App\Application\Http\Actions\User\ListUsersAction;
use App\Application\Http\Actions\User\ShowUserAction;
use App\Application\Http\Actions\User\UpdateUserAction;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    $app->group('/api', function (RouteCollectorProxy $api) {
        $api->group('/users', function (RouteCollectorProxy $users) {
            $users->get('', ListUsersAction::class);
            $users->post('', CreateUserAction::class);
            $users->group('/{id}', function (RouteCollectorProxy $user) {
                $user->delete('', DeleteUserAction::class);
                $user->get('', ShowUserAction::class);
                $user->put('', UpdateUserAction::class);
            });
        });
    });
};
