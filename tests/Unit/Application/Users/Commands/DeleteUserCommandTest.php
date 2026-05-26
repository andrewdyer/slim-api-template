<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Users\Commands;

use App\Application\Users\Commands\DeleteUserCommand;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for DeleteUserCommand.
 */
final class DeleteUserCommandTest extends TestCase
{
    /**
     * Asserts that the command stores the provided ID as a public property.
     */
    public function testCommandStoresProvidedId(): void
    {
        $command = new DeleteUserCommand(id: 42);

        $this->assertSame(42, $command->id);
    }
}
