<?php

declare(strict_types=1);

namespace App\Application\Users\Actions;

use AndrewDyer\Actions\AbstractAction;
use AndrewDyer\CommandBus\CommandBus;
use App\Application\Users\Commands\DeleteUserCommand;
use App\Application\Users\Exceptions\UserNotFoundException;
use JsonException;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Handles deleting a user via HTTP.
 */
final class DeleteUserAction extends AbstractAction
{
    /**
     * Creates a new DeleteUserAction with the required dependencies.
     *
     * @param CommandBus $commandBus The command bus used to dispatch commands.
     */
    public function __construct(private readonly CommandBus $commandBus)
    {
    }

    /**
     * Handles the deletion of a user.
     *
     * @return Response              A 204 JSON response with no body content.
     * @throws UserNotFoundException If no user exists with the given ID.
     * @throws JsonException         If the request body contains invalid JSON.
     */
    protected function handle(): Response
    {
        $userId = (int)$this->resolveArg('id');

        $this->commandBus->dispatch(new DeleteUserCommand($userId));

        return $this->ok(null, null, 204);
    }
}
