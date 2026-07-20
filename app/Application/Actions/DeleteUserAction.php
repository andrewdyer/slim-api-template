<?php

declare(strict_types=1);

namespace App\Application\Actions;

use AndrewDyer\Actions\AbstractAction;
use App\Application\Exceptions\UserNotFoundException;
use App\Application\Services\UserService;
use JsonException;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Handles deleting a user via HTTP.
 */
final class DeleteUserAction extends AbstractAction
{
    /**
     * Creates a new CreateUserAction with the required dependencies.
     *
     * @param UserService $userService The service that handles user application logic.
     */
    public function __construct(protected readonly UserService $userService)
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

        $this->userService->delete($userId);

        return $this->ok(null, null, 204);
    }
}
