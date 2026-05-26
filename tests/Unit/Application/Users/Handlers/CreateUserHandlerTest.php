<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Users\Handlers;

use App\Application\Users\Commands\CreateUserCommand;
use App\Application\Users\Handlers\CreateUserHandler;
use App\Infrastructure\Persistence\User\InMemoryUserRepository;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for CreateUserHandler.
 */
final class CreateUserHandlerTest extends TestCase
{
    /**
     * The handler under test.
     */
    private CreateUserHandler $handler;

    /**
     * Creates the handler instance before each test.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->handler = new CreateUserHandler(new InMemoryUserRepository());
    }

    /**
     * Asserts that a User entity with the correct data is returned after a successful creation.
     */
    public function testCreatesUserWhenValidDataIsProvided(): void
    {
        $command = new CreateUserCommand(
            firstName: 'Jane',
            lastName: 'Doe',
            email: 'jane@example.com',
        );

        $user = $this->handler->handle($command);

        $this->assertSame('Jane', $user->getFirstName());
        $this->assertSame('Doe', $user->getLastName());
        $this->assertSame('jane@example.com', $user->getEmail());
        $this->assertIsInt($user->getId());
    }
}
