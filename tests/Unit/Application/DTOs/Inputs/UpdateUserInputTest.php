<?php

declare(strict_types=1);

namespace Tests\Unit\Application\DTOs\Inputs;

use AndrewDyer\Actions\Exceptions\BadRequestException;
use App\Application\DTOs\Inputs\UpdateUserInput;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for UpdateUserInput.
 */
final class UpdateUserInputTest extends TestCase
{
    /**
     * Asserts that absent fields are set to null, allowing only the provided fields to be updated.
     */
    public function testAllowsPartialUpdateWhenSomeFieldsAreOmitted(): void
    {
        $input = UpdateUserInput::fromArray(7, ['first_name' => 'Janet']);

        $this->assertSame(7, $input->id);
        $this->assertSame('Janet', $input->firstName);
        $this->assertNull($input->lastName);
        $this->assertNull($input->email);
    }

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

        $input = UpdateUserInput::fromArray(42, $payload);

        $this->assertSame(42, $input->id);
        $this->assertSame('Jane', $input->firstName);
        $this->assertSame('Doe', $input->lastName);
        $this->assertSame('jane@example.com', $input->email);
    }

    /**
     * Asserts that all optional fields default to null when an empty array is provided.
     */
    public function testDefaultsFieldsToNullWhenPayloadIsEmpty(): void
    {
        $input = UpdateUserInput::fromArray(9, []);

        $this->assertNull($input->firstName);
        $this->assertNull($input->lastName);
        $this->assertNull($input->email);
    }

    /**
     * Asserts that a BadRequestException is thrown when the email address is not validly formatted.
     */
    public function testThrowsBadRequestExceptionWhenEmailIsInvalid(): void
    {
        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('Invalid email address');

        UpdateUserInput::fromArray(1, ['email' => 'not-an-email']);
    }
}
