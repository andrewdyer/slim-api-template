<?php

declare(strict_types=1);

namespace App\Application\Actions;

use AndrewDyer\Actions\AbstractAction;
use App\Application\DTOs\Output\UserOutput;
use App\Application\Exceptions\UserNotFoundException;
use App\Application\Services\UserService;
use JsonException;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Handles retrieving a single user by ID via HTTP.
 */
final class ShowUserAction extends AbstractAction
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
