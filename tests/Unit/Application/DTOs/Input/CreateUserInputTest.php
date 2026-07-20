<?php

declare(strict_types=1);

namespace Tests\Unit\Application\DTOs\Input;

use App\Application\DTOs\Input\CreateUserInput;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for CreateUserInput.
 */
final class CreateUserInputTest extends TestCase
{
    /**
     * Asserts that an input object is successfully created when all required fields are present in the input array.
     */
    public function testCreatesInstanceWhenAllFieldsAreProvided(): void
    {
        $payload = [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane@example.com',
        ];

        $input = CreateUserInput::fromArray($payload);

        $this->assertSame('Jane', $input->firstName);
        $this->assertSame('Doe', $input->lastName);
        $this->assertSame('jane@example.com', $input->email);
    }

    /**
     * Asserts that an InvalidArgumentException is thrown when one or more required fields are absent.
     *
     * @param array<string, string> $payload The incomplete input payload.
     */
    #[DataProvider('invalidPayloadProvider')]
    public function testThrowsInvalidArgumentExceptionWhenRequiredFieldsAreMissing(array $payload): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing required fields');

        CreateUserInput::fromArray($payload);
    }

    /**
     * Provides input arrays with one or more missing required fields.
     *
     * @return array<string, array{array<string, string>}>
     */
    public static function invalidPayloadProvider(): array
    {
        return [
            'missing first name' => [
                [
                    'last_name' => 'Doe',
                    'email' => 'jane@example.com',
                ],
            ],
            'missing last name' => [
                [
                    'first_name' => 'Jane',
                    'email' => 'jane@example.com',
                ],
            ],
            'missing email' => [
                [
                    'first_name' => 'Jane',
                    'last_name' => 'Doe',
                ],
            ],
            'empty payload' => [[]],
        ];
    }
}
