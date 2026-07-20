<?php

declare(strict_types=1);

namespace Tests\Unit\Application\DTOs;

use App\Application\DTOs\UserResponseDTO;
use App\Domain\Models\User;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for UserResponseDTO.
 */
final class UserResponseDTOTest extends TestCase
{
    /**
     * Asserts that the DTO is correctly populated from a domain User entity.
     */
    public function testCreatesInstanceWhenDomainUserIsProvided(): void
    {
        $user = new User(1, 'Jane', 'Doe', 'jane@example.com');

        $dto = UserResponseDTO::fromDomain($user);

        $this->assertSame(1, $dto->id);
        $this->assertSame('Jane', $dto->firstName);
        $this->assertSame('Doe', $dto->lastName);
        $this->assertSame('jane@example.com', $dto->email);
    }

    /**
     * Asserts that jsonSerialize returns an associative array with the expected keys and values.
     */
    public function testReturnsAssociativeArrayWhenSerialized(): void
    {
        $dto = new UserResponseDTO(2, 'John', 'Smith', 'john@example.com');

        $this->assertSame([
            'id' => 2,
            'firstName' => 'John',
            'lastName' => 'Smith',
            'email' => 'john@example.com',
        ], $dto->jsonSerialize());
    }
}
