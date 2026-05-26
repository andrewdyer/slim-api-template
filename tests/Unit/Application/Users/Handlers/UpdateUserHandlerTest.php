<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Users\Handlers;

use App\Application\Users\Commands\UpdateUserCommand;
use App\Application\Users\Exceptions\UserNotFoundException;
use App\Application\Users\Handlers\UpdateUserHandler;
use App\Infrastructure\Persistence\User\InMemoryUserRepository;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for UpdateUserHandler.
 */
final class UpdateUserHandlerTest extends TestCase
{
    /**
     * The handler under test.
     */
    private UpdateUserHandler $handler;

    /**
     * Creates the handler instance before each test.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->handler = new UpdateUserHandler(new InMemoryUserRepository());
    }

    /**
     * Asserts that all provided fields are applied and the updated User entity is returned.
     */
    public function testUpdatesUserWhenUserExists(): void
    {
        $command = new UpdateUserCommand(
            id: 1,
            firstName: 'William',
            lastName: 'French III',
            email: 'william.gates@example.com',
        );

        $user = $this->handler->handle($command);

        $this->assertSame(1, $user->getId());
        $this->assertSame('William', $user->getFirstName());
        $this->assertSame('French III', $user->getLastName());
        $this->assertSame('william.gates@example.com', $user->getEmail());
    }

    /**
     * Asserts that fields omitted from the update command retain their original values.
     */
    public function testPreservesUnchangedFieldsWhenPartialDataIsProvided(): void
    {
        $command = new UpdateUserCommand(
            id: 2,
            firstName: 'Stephen',
        );

        $user = $this->handler->handle($command);

        $this->assertSame(2, $user->getId());
        $this->assertSame('Stephen', $user->getFirstName());
        $this->assertSame('Anderson', $user->getLastName());
        $this->assertSame('charlotte.anderson@example.com', $user->getEmail());
    }

    /**
     * Asserts that UserNotFoundException is thrown when attempting to update a non-existent user.
     */
    public function testThrowsUserNotFoundExceptionWhenUpdatingNonExistentUser(): void
    {
        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage('User with ID 999 not found.');

        $this->handler->handle(new UpdateUserCommand(id: 999, firstName: 'Test'));
    }
}
