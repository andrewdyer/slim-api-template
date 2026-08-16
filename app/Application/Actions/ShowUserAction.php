<?php

declare(strict_types=1);

namespace App\Application\Actions;

use AndrewDyer\Actions\AbstractAction;
use App\Application\DTOs\Outputs\UserOutput;
use App\Application\Exceptions\UserNotFoundException;
use App\Application\Services\UserService;
use JsonException;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface as Response;
use RuntimeException;

/**
 * Handles retrieving a single user by ID via HTTP.
 */
final class ShowUserAction extends AbstractAction
{
    /**
     * Creates a new ShowUserAction with the required dependencies.
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
     * @throws RuntimeException      If the id route argument is missing.
     * @throws UserNotFoundException If no user exists with the given ID.
     * @throws JsonException         If the request body contains invalid JSON.
     */
    #[OA\Get(
        path: '/users/{id}',
        operationId: 'showUser',
        summary: 'Retrieve a user',
        tags: ['Users'],
        parameters: [
            new OA\PathParameter(name: 'id', description: 'The unique identifier of the user.', schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'The requested user.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/UserOutput'),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(
                response: 404,
                description: 'The requested resource could not be found.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'error',
                            properties: [
                                new OA\Property(property: 'type', type: 'string', example: 'RESOURCE_NOT_FOUND'),
                                new OA\Property(property: 'description', type: 'string', example: 'User with ID 1 not found.'),
                            ],
                            type: 'object'
                        ),
                    ],
                    type: 'object'
                )
            ),
        ]
    )]
    protected function handle(): Response
    {
        $userId = (int)$this->resolveArg('id');

        $user = $this->userService->find($userId);

        $output = UserOutput::fromDomain($user);

        return $this->ok($output);
    }
}
