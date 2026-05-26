<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Users\Commands;

use App\Application\Users\Commands\CreateUserCommand;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for CreateUserCommand.
 */
final class CreateUserCommandTest extends TestCase
{
    /**
     * Asserts that the command stores the provided values as public properties.
     */
    public function testCommandStoresProvidedValues(): void
    {
        $command = new CreateUserCommand(
            firstName: 'Jane',
            lastName: 'Doe',
            email: 'jane@example.com',
        );

        $this->assertSame('Jane', $command->firstName);
        $this->assertSame('Doe', $command->lastName);
        $this->assertSame('jane@example.com', $command->email);
    }
}
