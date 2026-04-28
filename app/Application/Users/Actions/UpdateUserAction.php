<?php

declare(strict_types=1);

namespace App\Application\Users\Actions;

use App\Application\Users\DTOs\UpdateUserDTO;
use App\Application\Users\DTOs\UserResponseDTO;
use App\Application\Users\Exceptions\UserNotFoundException;
use JsonException;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Handles updating an existing user via HTTP.
 */
final class UpdateUserAction extends AbstractUserAction
{
    /**
     * Resolves the user ID from the route, applies the requested changes, and returns the updated user.
     *
     * @return Response              A 200 JSON response containing the updated user.
     * @throws UserNotFoundException If no user exists with the given ID.
     * @throws JsonException         If the request body contains invalid JSON.
     */
    protected function handle(): Response
    {
        $userId = (int)$this->resolveArg('id');

        $inputDto = UpdateUserDTO::fromArray($userId, $this->getParsedBody());

        $user = $this->userService->update($inputDto);

        $responseDto = UserResponseDTO::fromDomain($user);

        return $this->ok($responseDto);
    }
}
