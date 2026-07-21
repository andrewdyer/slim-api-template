<?php

declare(strict_types=1);

namespace App\Application\Actions;

use AndrewDyer\Actions\AbstractAction;
use App\Application\DTOs\Inputs\UpdateUserInput;
use App\Application\DTOs\Outputs\UserOutput;
use App\Application\Exceptions\UserNotFoundException;
use App\Application\Services\UserService;
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
     * @param UserService $userService The service that handles user application logic.
     */
    public function __construct(protected readonly UserService $userService)
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

        $input = UpdateUserInput::fromArray($userId, $this->getParsedBody());

        $user = $this->userService->update($input);

        $output = UserOutput::fromDomain($user);

        return $this->ok($output);
    }
}
