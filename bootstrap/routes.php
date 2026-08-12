<?php

declare(strict_types=1);

use App\Application\Actions\CreateUserAction;
use App\Application\Actions\DeleteUserAction;
use App\Application\Actions\ListUsersAction;
use App\Application\Actions\ShowUserAction;
use App\Application\Actions\UpdateUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

/**
 * Registers HTTP route definitions.
 */
return static function(App $app): void {
    $app->options('/{routes:.*}', function(Request $request, Response $response): Response {
        return $response;
    });

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
