<?php

declare(strict_types=1);

use App\Application\Users\Actions\CreateUserAction;
use App\Application\Users\Actions\DeleteUserAction;
use App\Application\Users\Actions\ListUsersAction;
use App\Application\Users\Actions\ShowUserAction;
use App\Application\Users\Actions\UpdateUserAction;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

/**
 * Registers HTTP route definitions.
 */
return function(App $app): void {
    $app->group('/api', function(Group $api) {
        $api->group('/v1', function(Group $v1) {
            $v1->group('/users', function(Group $users) {
                $users->get('', ListUsersAction::class);
                $users->post('', CreateUserAction::class);
                $users->get('/{id}', ShowUserAction::class);
                $users->put('/{id}', UpdateUserAction::class);
                $users->delete('/{id}', DeleteUserAction::class);
            });
        });
    });
};
