<?php

declare(strict_types=1);

namespace App\Application\Actions;

use App\Application\DTOs\Input\CreateUserInput;
use App\Application\DTOs\Output\UserOutput;
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
        $input = CreateUserInput::fromArray($this->getParsedBody());

        $user = $this->userService->create($input);

        $output = UserOutput::fromDomain($user);

        return $this->ok($output, null, 201);
    }
}
