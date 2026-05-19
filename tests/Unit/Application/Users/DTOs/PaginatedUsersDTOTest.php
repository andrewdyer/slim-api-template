<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Users\DTOs;

use App\Application\Users\DTOs\PaginatedUsersDTO;
use App\Application\Users\DTOs\UserResponseDTO;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for PaginatedUsersDTO.
 */
final class PaginatedUsersDTOTest extends TestCase
{
    /**
     * Asserts that the DTO is correctly instantiated with the provided pagination data.
     */
    public function testCreatesInstanceWhenAllFieldsAreProvided(): void
    {
        $users = [
            new UserResponseDTO(1, 'Jane', 'Doe', 'jane@example.com'),
            new UserResponseDTO(2, 'John', 'Smith', 'john@example.com'),
        ];

        $dto = new PaginatedUsersDTO(
            data: $users,
            total: 50,
            page: 2,
            perPage: 10,
            totalPages: 5
        );

        $this->assertSame($users, $dto->data);
        $this->assertSame(50, $dto->total);
        $this->assertSame(2, $dto->page);
        $this->assertSame(10, $dto->perPage);
        $this->assertSame(5, $dto->totalPages);
    }

    /**
     * Asserts that jsonSerialize returns the correct structure with data and meta keys.
     */
    public function testReturnsCorrectStructureWhenSerialized(): void
    {
        $users = [
            new UserResponseDTO(1, 'Jane', 'Doe', 'jane@example.com'),
            new UserResponseDTO(2, 'John', 'Smith', 'john@example.com'),
        ];

        $dto = new PaginatedUsersDTO(
            data: $users,
            total: 25,
            page: 1,
            perPage: 10,
            totalPages: 3
        );

        $serialized = $dto->jsonSerialize();

        $this->assertArrayHasKey('data', $serialized);
        $this->assertArrayHasKey('meta', $serialized);
        $this->assertSame($users, $serialized['data']);
        $this->assertSame([
            'total' => 25,
            'page' => 1,
            'perPage' => 10,
            'totalPages' => 3,
        ], $serialized['meta']);
    }

    /**
     * Asserts that the DTO handles an empty data array correctly.
     */
    public function testHandlesEmptyDataArray(): void
    {
        $dto = new PaginatedUsersDTO(
            data: [],
            total: 0,
            page: 1,
            perPage: 10,
            totalPages: 0
        );

        $this->assertSame([], $dto->data);
        $this->assertSame(0, $dto->total);
        $this->assertSame(0, $dto->totalPages);
    }

    /**
     * Asserts that jsonSerialize produces valid JSON when encoded.
     */
    public function testProducesValidJsonWhenEncoded(): void
    {
        $users = [
            new UserResponseDTO(1, 'Jane', 'Doe', 'jane@example.com'),
        ];

        $dto = new PaginatedUsersDTO(
            data: $users,
            total: 1,
            page: 1,
            perPage: 10,
            totalPages: 1
        );

        $json = json_encode($dto);

        $this->assertIsString($json);
        $this->assertJson($json);

        $decoded = json_decode($json, true);
        $this->assertArrayHasKey('data', $decoded);
        $this->assertArrayHasKey('meta', $decoded);
        $this->assertCount(1, $decoded['data']);
    }
}
