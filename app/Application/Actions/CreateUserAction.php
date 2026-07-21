<?php

declare(strict_types=1);

namespace App\Application\Actions;

use AndrewDyer\Actions\AbstractAction;
use App\Application\DTOs\Inputs\CreateUserInput;
use App\Application\DTOs\Outputs\UserOutput;
use App\Application\Services\UserService;
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
     * @param UserService $userService The service that handles user application logic.
     */
    public function __construct(protected readonly UserService $userService)
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
        $input = CreateUserInput::fromArray($this->getParsedBody());

        $user = $this->userService->create($input);

        $output = UserOutput::fromDomain($user);

        return $this->ok($output, null, 201);
    }
}
