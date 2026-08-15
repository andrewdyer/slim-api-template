<?php

declare(strict_types=1);

namespace App\Application\Actions;

use AndrewDyer\Actions\AbstractAction;
use App\Application\DTOs\Outputs\UserOutput;
use App\Application\Services\UserService;
use App\Domain\Models\User;
use JsonException;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Handles listing all users via HTTP.
 */
final class ListUsersAction extends AbstractAction
{
    /**
     * Creates a new ListUsersAction with the required dependencies.
     *
     * @param UserService $userService The service that handles user application logic.
     */
    public function __construct(protected readonly UserService $userService)
    {
    }

    /**
     * Handles the retrieval of paginated users.
     *
     * Invalid page or perPage values are silently clamped to their nearest valid boundary
     * rather than rejected with a 400. This keeps the API forgiving for UI consumers where
     * stale or out-of-range params are common. To enforce strict validation instead, replace
     * the clamping with a 400 error response.
     *
     * @return Response      A 200 JSON response containing paginated users and metadata.
     * @throws JsonException If the request body contains invalid JSON.
     */
    #[OA\Get(
        path: '/users',
        operationId: 'listUsers',
        summary: 'List users',
        tags: ['Users'],
        parameters: [
            new OA\QueryParameter(name: 'page', description: 'The 1-indexed page number.', required: false, schema: new OA\Schema(type: 'integer', default: 1)),
            new OA\QueryParameter(name: 'perPage', description: 'The number of users per page (max 100).', required: false, schema: new OA\Schema(type: 'integer', default: 10)),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'A page of users.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/UserOutput')),
                        new OA\Property(
                            property: 'meta',
                            properties: [
                                new OA\Property(property: 'total', type: 'integer', example: 42),
                                new OA\Property(property: 'page', type: 'integer', example: 1),
                                new OA\Property(property: 'perPage', type: 'integer', example: 10),
                                new OA\Property(property: 'totalPages', type: 'integer', example: 5),
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
        $page = max(1, (int)$this->resolveQueryParam('page', 1));
        $perPage = max(1, min(100, (int)$this->resolveQueryParam('perPage', 10)));

        ['users' => $users, 'total' => $total] = $this->userService->paginated($page, $perPage);

        $output = array_map(
            fn (User $user) => UserOutput::fromDomain($user),
            $users
        );

        return $this->ok(
            $output,
            [
                'total' => $total,
                'page' => $page,
                'perPage' => $perPage,
                'totalPages' => (int)ceil($total / $perPage),
            ]
        );
    }
}
