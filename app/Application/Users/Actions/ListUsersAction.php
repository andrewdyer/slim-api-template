<?php

declare(strict_types=1);

namespace App\Application\Users\Actions;

use App\Application\Users\DTOs\PaginatedUsersDTO;
use App\Application\Users\DTOs\UserResponseDTO;
use App\Domain\User\User;
use JsonException;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Handles listing all users via HTTP.
 */
final class ListUsersAction extends AbstractUserAction
{
    /**
     * Retrieves paginated users from the service and returns them with pagination metadata.
     *
     * @return Response      A 200 JSON response containing paginated users and metadata.
     * @throws JsonException If the request body contains invalid JSON.
     */
    protected function handle(): Response
    {
        $page = (int)$this->resolveQueryParam('page', 1);
        $perPage = (int)$this->resolveQueryParam('perPage', 10);

        $result = $this->userService->paginated($page, $perPage);

        $userData = array_map(
            fn (User $user) => UserResponseDTO::fromDomain($user),
            $result['users']
        );

        $totalPages = (int)ceil($result['total'] / $perPage);

        $paginatedDto = new PaginatedUsersDTO(
            data: $userData,
            total: $result['total'],
            page: $page,
            perPage: $perPage,
            totalPages: $totalPages,
        );

        return $this->ok($paginatedDto);
    }
}
