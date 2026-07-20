<?php

declare(strict_types=1);

namespace App\Application\Actions;

use AndrewDyer\Actions\AbstractAction;
use App\Application\DTOs\Output\UserOutput;
use App\Application\Services\UserService;
use App\Domain\Models\User;
use JsonException;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Handles listing all users via HTTP.
 */
final class ListUsersAction extends AbstractAction
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
