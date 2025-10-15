<?php

namespace Tests\Unit\Application\Users\DTO;

use App\Application\Users\DTO\CreateUserDTO;
use PHPUnit\Framework\TestCase;

final class CreateUserDTOTest extends TestCase
{
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

    public function testFromArrayWithEmptyArray(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing required fields');

        CreateUserDTO::fromArray([]);
    }

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
