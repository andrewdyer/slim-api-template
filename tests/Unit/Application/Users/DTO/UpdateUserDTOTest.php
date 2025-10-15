<?php

namespace Tests\Unit\Application\Users\DTO;

use App\Application\Users\DTO\UpdateUserDTO;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for the UpdateUserDTO data transfer object.
 *
 * This test class verifies that the UpdateUserDTO correctly handles
 * constructor parameters, array conversion, and type casting for
 * user update operations.
 */
final class UpdateUserDTOTest extends TestCase
{
    /**
     * Test constructor with all available fields populated.
     *
     * Verifies that when all constructor parameters are provided, the DTO
     * correctly stores and returns each value through its properties.
     *
     * @return void
     */
    public function testConstructorWithAllFields(): void
    {
        $dto = new UpdateUserDTO(
            id: 1,
            firstName: 'John',
            lastName: 'Doe',
            email: 'john.doe@example.com'
        );

        $this->assertSame(1, $dto->id);
        $this->assertSame('John', $dto->firstName);
        $this->assertSame('Doe', $dto->lastName);
        $this->assertSame('john.doe@example.com', $dto->email);
    }

    /**
     * Test constructor with only the required ID field.
     *
     * Verifies that when only the ID is provided, all optional fields
     * are correctly set to null by default.
     *
     * @return void
     */
    public function testConstructorWithOnlyId(): void
    {
        $dto = new UpdateUserDTO(id: 1);

        $this->assertSame(1, $dto->id);
        $this->assertNull($dto->firstName);
        $this->assertNull($dto->lastName);
        $this->assertNull($dto->email);
    }

    /**
     * Test constructor with partial field population.
     *
     * Verifies that when some optional fields are provided and others
     * are omitted, the DTO correctly handles the partial data.
     *
     * @return void
     */
    public function testConstructorWithPartialFields(): void
    {
        $dto = new UpdateUserDTO(
            id: 1,
            firstName: 'John'
        );

        $this->assertSame(1, $dto->id);
        $this->assertSame('John', $dto->firstName);
        $this->assertNull($dto->lastName);
        $this->assertNull($dto->email);
    }

    /**
     * Test fromArray method with type casting to string.
     *
     * Verifies that the fromArray factory method correctly converts
     * non-string values (like integers) to strings for all fields.
     *
     * @return void
     */
    public function testFromArrayCastsToString(): void
    {
        $data = [
            'first_name' => 123,
            'last_name' => 456,
            'email' => 789,
        ];

        $dto = UpdateUserDTO::fromArray(6, $data);

        $this->assertSame(6, $dto->id);
        $this->assertSame('123', $dto->firstName);
        $this->assertSame('456', $dto->lastName);
        $this->assertSame('789', $dto->email);
    }

    /**
     * Test fromArray method with all fields populated.
     *
     * Verifies that the fromArray factory method correctly maps array keys
     * to DTO properties when all expected fields are present in the data.
     *
     * @return void
     */
    public function testFromArrayWithAllFields(): void
    {
        $data = [
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane.smith@example.com',
        ];

        $dto = UpdateUserDTO::fromArray(2, $data);

        $this->assertSame(2, $dto->id);
        $this->assertSame('Jane', $dto->firstName);
        $this->assertSame('Smith', $dto->lastName);
        $this->assertSame('jane.smith@example.com', $dto->email);
    }

    /**
     * Test fromArray method with empty data array.
     *
     * Verifies that when an empty array is passed to fromArray, the DTO
     * is created with only the ID set and all other fields as null.
     *
     * @return void
     */
    public function testFromArrayWithEmptyData(): void
    {
        $dto = UpdateUserDTO::fromArray(4, []);

        $this->assertSame(4, $dto->id);
        $this->assertNull($dto->firstName);
        $this->assertNull($dto->lastName);
        $this->assertNull($dto->email);
    }

    /**
     * Test fromArray method with mixed field presence.
     *
     * Verifies that the fromArray method correctly handles arrays where
     * some fields are present and others are omitted entirely.
     *
     * @return void
     */
    public function testFromArrayWithMixedFields(): void
    {
        $data = [
            'first_name' => 'Updated',
            'email' => 'updated@example.com',
            // lastName intentionally omitted
        ];

        $dto = UpdateUserDTO::fromArray(5, $data);

        $this->assertSame(5, $dto->id);
        $this->assertSame('Updated', $dto->firstName);
        $this->assertNull($dto->lastName);
        $this->assertSame('updated@example.com', $dto->email);
    }

    /**
     * Test fromArray method with explicit null values.
     *
     * Verifies that when array values are explicitly set to null,
     * the DTO correctly preserves these null values rather than
     * attempting to convert them.
     *
     * @return void
     */
    public function testFromArrayWithNullValues(): void
    {
        $data = [
            'first_name' => null,
            'last_name' => 'Valid',
            'email' => null,
        ];

        $dto = UpdateUserDTO::fromArray(7, $data);

        $this->assertSame(7, $dto->id);
        $this->assertNull($dto->firstName);
        $this->assertSame('Valid', $dto->lastName);
        $this->assertNull($dto->email);
    }

    /**
     * Test fromArray method with only one field specified.
     *
     * Verifies that when only a single field is provided in the array,
     * that field is set correctly while others remain null.
     *
     * @return void
     */
    public function testFromArrayWithPartialFields(): void
    {
        $data = [
            'first_name' => 'UpdatedName',
        ];

        $dto = UpdateUserDTO::fromArray(3, $data);

        $this->assertSame(3, $dto->id);
        $this->assertSame('UpdatedName', $dto->firstName);
        $this->assertNull($dto->lastName);
        $this->assertNull($dto->email);
    }
}
