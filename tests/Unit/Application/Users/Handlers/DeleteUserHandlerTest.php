<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Users\Handlers;

use App\Application\Users\Commands\DeleteUserCommand;
use App\Application\Users\Exceptions\UserNotFoundException;
use App\Application\Users\Handlers\DeleteUserHandler;
use App\Infrastructure\Persistence\User\InMemoryUserRepository;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for DeleteUserHandler.
 */
final class DeleteUserHandlerTest extends TestCase
{
    /**
     * The handler under test.
     */
    private DeleteUserHandler $handler;

    /**
     * The in-memory repository used by the handler.
     */
    private InMemoryUserRepository $repository;

    /**
     * Creates the handler and repository instances before each test.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new InMemoryUserRepository();
        $this->handler = new DeleteUserHandler($this->repository);
    }

    /**
     * Asserts that the user can no longer be found after a successful deletion.
     */
    public function testDeletesUserWhenUserExists(): void
    {
        $this->handler->handle(new DeleteUserCommand(id: 1));

        $this->assertNull($this->repository->findById(1));
    }

    /**
     * Asserts that UserNotFoundException is thrown when deleting a non-existent user.
     */
    public function testThrowsUserNotFoundExceptionWhenDeletingNonExistentUser(): void
    {
        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage('User with ID 999 not found.');

        $this->handler->handle(new DeleteUserCommand(id: 999));
    }
}
