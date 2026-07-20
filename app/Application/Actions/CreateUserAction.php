<?php

declare(strict_types=1);

namespace App\Application\Actions;

use App\Application\DTOs\Input\CreateUserDTO;
use App\Application\DTOs\Output\UserResponseDTO;
use InvalidArgumentException;
use JsonException;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Handles creating a new user via HTTP.
 */
final class CreateUserAction extends AbstractUserAction
{
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

        $user = $this->userService->create($inputDto);

        $responseDto = UserResponseDTO::fromDomain($user);

        return $this->ok($responseDto, null, 201);
    }
}
