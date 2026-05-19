<?php

declare(strict_types=1);

namespace App\Application\Users\DTOs;

use JsonSerializable;

/**
 * Represents a paginated collection of users with metadata.
 */
final readonly class PaginatedUsersDTO implements JsonSerializable
{
    /**
     * @param UserResponseDTO[] $data       The array of user DTOs for the current page.
     * @param int               $total      The total number of users across all pages.
     * @param int               $page       The current page number (1-indexed).
     * @param int               $perPage    The number of items per page.
     * @param int               $totalPages The total number of available pages.
     */
    public function __construct(
        public array $data,
        public int $total,
        public int $page,
        public int $perPage,
        public int $totalPages,
    ) {
    }

    /**
     * Serializes the paginated result to a JSON-compatible array.
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'data' => $this->data,
            'meta' => [
                'total' => $this->total,
                'page' => $this->page,
                'perPage' => $this->perPage,
                'totalPages' => $this->totalPages,
            ],
        ];
    }
}
