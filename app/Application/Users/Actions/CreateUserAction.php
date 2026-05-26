<?php

declare(strict_types=1);

namespace App\Application\Users\Actions;

use AndrewDyer\Actions\AbstractAction;
use AndrewDyer\CommandBus\CommandBus;
use App\Application\Users\Commands\CreateUserCommand;
use App\Application\Users\DTOs\CreateUserDTO;
use App\Application\Users\DTOs\UserResponseDTO;
use InvalidArgumentException;
use JsonException;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Handles creating a new user via HTTP.
 */
final class CreateUserAction extends AbstractAction
{
    /**
     * Creates a new CreateUserAction with the required dependencies.
     *
     * @param CommandBus $commandBus The command bus used to dispatch commands.
     */
    public function __construct(private readonly CommandBus $commandBus)
    {
    }

    /**
     * Handles the creation of a new user.
     *
     * @return Response                 A 201 JSON response containing the newly created user.
     * @throws InvalidArgumentException If required fields are missing from the request body.
     * @throws JsonException            If the request body contains invalid JSON.
     */
    protected function handle(): Response
    {
        $inputDto = CreateUserDTO::fromArray($this->getParsedBody());

        $user = $this->commandBus->dispatch(new CreateUserCommand(
            $inputDto->firstName,
            $inputDto->lastName,
            $inputDto->email,
        ));

        $responseDto = UserResponseDTO::fromDomain($user);

        return $this->ok($responseDto, null, 201);
    }
}
