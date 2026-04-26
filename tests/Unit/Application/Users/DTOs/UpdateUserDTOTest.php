<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Users\DTOs;

use App\Application\Users\DTOs\UpdateUserDTO;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for UpdateUserDTO.
 */
final class UpdateUserDTOTest extends TestCase
{
    /**
     * Asserts that all fields are correctly mapped when a complete input array is provided.
     */
    public function testCreatesInstanceWhenAllFieldsAreProvided(): void
    {
        $payload = [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane@example.com',
        ];

        $dto = UpdateUserDTO::fromArray(42, $payload);

        $this->assertSame(42, $dto->id);
        $this->assertSame('Jane', $dto->firstName);
        $this->assertSame('Doe', $dto->lastName);
        $this->assertSame('jane@example.com', $dto->email);
    }

    /**
     * Asserts that absent fields are set to null, allowing only the provided fields to be updated.
     */
    public function testAllowsPartialUpdateWhenSomeFieldsAreOmitted(): void
    {
        $dto = UpdateUserDTO::fromArray(7, ['first_name' => 'Janet']);

        $this->assertSame(7, $dto->id);
        $this->assertSame('Janet', $dto->firstName);
        $this->assertNull($dto->lastName);
        $this->assertNull($dto->email);
    }

    /**
     * Asserts that all optional fields default to null when an empty array is provided.
     */
    public function testDefaultsFieldsToNullWhenPayloadIsEmpty(): void
    {
        $dto = UpdateUserDTO::fromArray(9, []);

        $this->assertNull($dto->firstName);
        $this->assertNull($dto->lastName);
        $this->assertNull($dto->email);
    }
}
