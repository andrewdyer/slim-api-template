<?php

namespace Tests\Unit\Application\Users\DTO;

use App\Application\Users\DTO\UpdateUserDTO;
use PHPUnit\Framework\TestCase;

final class UpdateUserDTOTest extends TestCase
{
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

    public function testConstructorWithOnlyId(): void
    {
        $dto = new UpdateUserDTO(id: 1);

        $this->assertSame(1, $dto->id);
        $this->assertNull($dto->firstName);
        $this->assertNull($dto->lastName);
        $this->assertNull($dto->email);
    }

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

    public function testFromArrayWithEmptyData(): void
    {
        $dto = UpdateUserDTO::fromArray(4, []);

        $this->assertSame(4, $dto->id);
        $this->assertNull($dto->firstName);
        $this->assertNull($dto->lastName);
        $this->assertNull($dto->email);
    }

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
