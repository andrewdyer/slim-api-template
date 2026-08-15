<?php

declare(strict_types=1);

namespace App\Application\Actions;

use AndrewDyer\Actions\AbstractAction;
use AndrewDyer\Actions\Exceptions\BadRequestException;
use App\Application\DTOs\Inputs\UpdateUserInput;
use App\Application\DTOs\Outputs\UserOutput;
use App\Application\Exceptions\UserEmailAlreadyExistsException;
use App\Application\Exceptions\UserNotFoundException;
use App\Application\Services\UserService;
use JsonException;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface as Response;
use RuntimeException;

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
     * @return Response                        A 200 JSON response containing the updated user.
     * @throws RuntimeException                If the id route argument is missing, or the request body does not decode to an array.
     * @throws UserNotFoundException           If no user exists with the given ID.
     * @throws UserEmailAlreadyExistsException If another user with the given email already exists.
     * @throws BadRequestException             If an email address is provided but is not validly formatted.
     * @throws JsonException                   If the request body contains invalid JSON.
     */
    #[OA\Put(
        path: '/users/{id}',
        operationId: 'updateUser',
        summary: 'Update a user',
        requestBody: new OA\RequestBody(
            required: false,
            content: new OA\JsonContent(ref: '#/components/schemas/UpdateUserInput')
        ),
        tags: ['Users'],
        parameters: [
            new OA\PathParameter(name: 'id', description: 'The unique identifier of the user to update.', schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'The updated user.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/UserOutput'),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(ref: '#/components/responses/BadRequest', response: 400),
            new OA\Response(ref: '#/components/responses/NotFound', response: 404),
            new OA\Response(ref: '#/components/responses/Conflict', response: 409),
        ]
    )]
    protected function handle(): Response
    {
        $userId = (int)$this->resolveArg('id');

        $input = UpdateUserInput::fromArray($userId, $this->getParsedBody());

        $user = $this->userService->update($input);

        $output = UserOutput::fromDomain($user);

        return $this->ok($output);
    }
}
