<?php

declare(strict_types=1);

namespace App\Application\Users\Actions;

use AndrewDyer\Actions\AbstractAction;
use AndrewDyer\CommandBus\CommandBus;
use App\Application\Users\Commands\UpdateUserCommand;
use App\Application\Users\DTOs\UpdateUserDTO;
use App\Application\Users\DTOs\UserResponseDTO;
use App\Application\Users\Exceptions\UserNotFoundException;
use JsonException;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Handles updating an existing user via HTTP.
 */
final class UpdateUserAction extends AbstractAction
{
    /**
     * Creates a new UpdateUserAction with the required dependencies.
     *
     * @param CommandBus $commandBus The command bus used to dispatch commands.
     */
    public function __construct(private readonly CommandBus $commandBus)
    {
    }

    /**
     * Handles the update of an existing user.
     *
     * @return Response              A 200 JSON response containing the updated user.
     * @throws UserNotFoundException If no user exists with the given ID.
     * @throws JsonException         If the request body contains invalid JSON.
     */
    protected function handle(): Response
    {
        $userId = (int)$this->resolveArg('id');

        $inputDto = UpdateUserDTO::fromArray($userId, $this->getParsedBody());

        $user = $this->commandBus->dispatch(new UpdateUserCommand(
            $inputDto->id,
            $inputDto->firstName,
            $inputDto->lastName,
            $inputDto->email,
        ));

        $responseDto = UserResponseDTO::fromDomain($user);

        return $this->ok($responseDto);
    }
}
