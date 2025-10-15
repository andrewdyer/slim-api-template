<?php

use App\Application\Http\Actions\User\CreateUserAction;
use App\Application\Http\Actions\User\DeleteUserAction;
use App\Application\Http\Actions\User\ListUsersAction;
use App\Application\Http\Actions\User\ShowUserAction;
use App\Application\Http\Actions\User\UpdateUserAction;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

/**
 * Define application routes and group them logically.
 *
 * This file registers all HTTP routes for the application using Slim's
 * routing system. Routes are organized in groups for better maintainability
 * and to apply middleware consistently.
 *
 * @param Slim\App $app The Slim application instance to register routes with
 * @return void
 */
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
