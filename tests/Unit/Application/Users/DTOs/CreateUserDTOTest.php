<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Users\DTOs;

use App\Application\Users\DTOs\CreateUserDTO;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for CreateUserDTO.
 */
final class CreateUserDTOTest extends TestCase
{
    /**
     * Asserts that a DTO is successfully created when all required fields are present in the input array.
     */
    public function testCreatesInstanceWhenAllFieldsAreProvided(): void
    {
        $payload = [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane@example.com',
        ];

        $dto = CreateUserDTO::fromArray($payload);

        $this->assertSame('Jane', $dto->firstName);
        $this->assertSame('Doe', $dto->lastName);
        $this->assertSame('jane@example.com', $dto->email);
    }

    /**
     * Asserts that an InvalidArgumentException is thrown when one or more required fields are absent.
     *
     * @dataProvider invalidPayloadProvider
     */
    public function testThrowsInvalidArgumentExceptionWhenRequiredFieldsAreMissing(array $payload): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing required fields');

        CreateUserDTO::fromArray($payload);
    }

    /**
     * Provides input arrays with one or more missing required fields.
     *
     * @return array<string, array<mixed>> Named sets of invalid payloads.
     */
    public static function invalidPayloadProvider(): array
    {
        return [
            'missing first name' => [[
                'last_name' => 'Doe',
                'email' => 'jane@example.com',
            ]],
            'missing last name' => [[
                'first_name' => 'Jane',
                'email' => 'jane@example.com',
            ]],
            'missing email' => [[
                'first_name' => 'Jane',
                'last_name' => 'Doe',
            ]],
            'empty payload' => [[]],
        ];
    }
}
