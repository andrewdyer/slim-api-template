<?php

declare(strict_types=1);

namespace App\Application\Actions;

use App\Application\DTOs\Output\UserOutput;
use App\Application\Exceptions\UserNotFoundException;
use JsonException;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Handles retrieving a single user by ID via HTTP.
 */
final class ShowUserAction extends AbstractUserAction
{
    /**
     * Handles the retrieval of a single user by ID.
     *
     * @return Response              A 200 JSON response containing the requested user.
     * @throws UserNotFoundException If no user exists with the given ID.
     * @throws JsonException         If the request body contains invalid JSON.
     */
    protected function handle(): Response
    {
        $userId = (int)$this->resolveArg('id');

        $user = $this->userService->find($userId);

        $output = UserOutput::fromDomain($user);

        return $this->ok($output);
    }
}
