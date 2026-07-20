<?php

declare(strict_types=1);

namespace App\Application\Actions;

use App\Application\DTOs\Input\UpdateUserInput;
use App\Application\DTOs\Output\UserOutput;
use App\Application\Exceptions\UserNotFoundException;
use JsonException;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Handles updating an existing user via HTTP.
 */
final class UpdateUserAction extends AbstractUserAction
{
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

        $input = UpdateUserInput::fromArray($userId, $this->getParsedBody());

        $user = $this->userService->update($input);

        $output = UserOutput::fromDomain($user);

        return $this->ok($output);
    }
}
