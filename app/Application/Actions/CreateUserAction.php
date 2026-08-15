<?php

declare(strict_types=1);

namespace App\Application\Actions;

use AndrewDyer\Actions\AbstractAction;
use AndrewDyer\Actions\Exceptions\BadRequestException;
use App\Application\DTOs\Inputs\CreateUserInput;
use App\Application\DTOs\Outputs\UserOutput;
use App\Application\Exceptions\UserEmailAlreadyExistsException;
use App\Application\Services\UserService;
use JsonException;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface as Response;
use RuntimeException;

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
     * @return Response                        A 201 JSON response containing the newly created user.
     * @throws RuntimeException                If the request body does not decode to an array.
     * @throws BadRequestException             If required fields are missing from the request body.
     * @throws UserEmailAlreadyExistsException If a user with the given email already exists.
     * @throws JsonException                   If the request body contains invalid JSON.
     */
    #[OA\Post(
        path: '/users',
        operationId: 'createUser',
        summary: 'Create a user',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/CreateUserInput')
        ),
        tags: ['Users'],
        responses: [
            new OA\Response(
                response: 201,
                description: 'The newly created user.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/UserOutput'),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(ref: '#/components/responses/BadRequest', response: 400),
            new OA\Response(ref: '#/components/responses/Conflict', response: 409),
        ]
    )]
    protected function handle(): Response
    {
        $input = CreateUserInput::fromArray($this->getParsedBody());

        $user = $this->userService->create($input);

        $output = UserOutput::fromDomain($user);

        return $this->ok($output, null, 201);
    }
}
