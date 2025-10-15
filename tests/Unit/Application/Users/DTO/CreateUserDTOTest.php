<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Users\DTO;

use App\Application\Users\DTO\CreateUserDTO;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the CreateUserDTO data transfer object.
 *
 * This test class verifies the behavior of the CreateUserDTO, including
 * constructor functionality, static factory methods, and input validation.
 */
final class CreateUserDTOTest extends TestCase
{
    /**
     * Test that the constructor sets all properties correctly.
     *
     * This test verifies that the CreateUserDTO constructor properly assigns
     * the provided values to the corresponding public properties.
     *
     * @return void
     */
    public function testConstructorSetsPropertiesCorrectly(): void
    {
        $dto = new CreateUserDTO(
            firstName: 'John',
            lastName: 'Doe',
            email: 'john.doe@example.com'
        );

        $this->assertSame('John', $dto->firstName);
        $this->assertSame('Doe', $dto->lastName);
        $this->assertSame('john.doe@example.com', $dto->email);
    }

    /**
     * Test fromArray factory method with complete data.
     *
     * This test verifies that the fromArray method correctly creates a DTO
     * instance when provided with a complete data array containing all
     * required fields.
     *
     * @return void
     */
    public function testFromArrayWithCompleteData(): void
    {
        $data = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
        ];

        $dto = CreateUserDTO::fromArray($data);

        $this->assertSame('John', $dto->firstName);
        $this->assertSame('Doe', $dto->lastName);
        $this->assertSame('john.doe@example.com', $dto->email);
    }

    /**
     * Test fromArray factory method with empty array.
     *
     * This test verifies that the fromArray method throws an appropriate
     * exception when provided with an empty array that lacks required fields.
     *
     * @return void
     */
    public function testFromArrayWithEmptyArray(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing required fields');

        CreateUserDTO::fromArray([]);
    }

    /**
     * Test fromArray factory method ignores extra data.
     *
     * This test verifies that the fromArray method correctly handles input
     * arrays that contain additional fields beyond the required ones,
     * ignoring the extra data while processing the necessary fields.
     *
     * @return void
     */
    public function testFromArrayWithExtraData(): void
    {
        $data = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'extraField' => 'ignored',
        ];

        $dto = CreateUserDTO::fromArray($data);

        $this->assertSame('John', $dto->firstName);
        $this->assertSame('Doe', $dto->lastName);
        $this->assertSame('john.doe@example.com', $dto->email);
    }

    /**
     * Test fromArray factory method with missing required data.
     *
     * This test verifies that the fromArray method throws an appropriate
     * exception when the input array is missing one or more required fields.
     *
     * @return void
     */
    public function testFromArrayWithMissingData(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing required fields');

        $data = [
            'first_name' => 'John',
            // last_name missing
            'email' => 'john.doe@example.com',
        ];

        CreateUserDTO::fromArray($data);
    }
}
