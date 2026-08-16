<?php

declare(strict_types=1);

namespace App\Application\Actions;

use AndrewDyer\Actions\AbstractAction;
use App\Application\Exceptions\UserNotFoundException;
use App\Application\Services\UserService;
use JsonException;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface as Response;
use RuntimeException;

/**
 * Handles deleting a user via HTTP.
 */
final class DeleteUserAction extends AbstractAction
{
    /**
     * Creates a new DeleteUserAction with the required dependencies.
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
     * @throws RuntimeException      If the id route argument is missing.
     * @throws UserNotFoundException If no user exists with the given ID.
     * @throws JsonException         If the request body contains invalid JSON.
     */
    #[OA\Delete(
        path: '/users/{id}',
        operationId: 'deleteUser',
        summary: 'Delete a user',
        tags: ['Users'],
        parameters: [
            new OA\PathParameter(name: 'id', description: 'The unique identifier of the user to delete.', schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 204, description: 'The user was deleted successfully.'),
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

        $this->userService->delete($userId);

        return $this->ok(null, null, 204);
    }
}
