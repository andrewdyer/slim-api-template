<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Users\Commands;

use App\Application\Users\Commands\UpdateUserCommand;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for UpdateUserCommand.
 */
final class UpdateUserCommandTest extends TestCase
{
    /**
     * Asserts that the command stores all provided values as public properties.
     */
    public function testCommandStoresProvidedValues(): void
    {
        $command = new UpdateUserCommand(
            id: 1,
            firstName: 'Jane',
            lastName: 'Doe',
            email: 'jane@example.com',
        );

        $this->assertSame(1, $command->id);
        $this->assertSame('Jane', $command->firstName);
        $this->assertSame('Doe', $command->lastName);
        $this->assertSame('jane@example.com', $command->email);
    }

    /**
     * Asserts that optional fields default to null when not provided.
     */
    public function testCommandDefaultsOptionalFieldsToNull(): void
    {
        $command = new UpdateUserCommand(id: 5);

        $this->assertSame(5, $command->id);
        $this->assertNull($command->firstName);
        $this->assertNull($command->lastName);
        $this->assertNull($command->email);
    }

    /**
     * Asserts that only selected optional fields can be partially set.
     */
    public function testCommandAllowsPartialUpdate(): void
    {
        $command = new UpdateUserCommand(id: 3, email: 'newemail@example.com');

        $this->assertSame(3, $command->id);
        $this->assertNull($command->firstName);
        $this->assertNull($command->lastName);
        $this->assertSame('newemail@example.com', $command->email);
    }
}
