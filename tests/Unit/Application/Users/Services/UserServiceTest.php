<?php

namespace Tests\Unit\Application\Users\Services;

use App\Application\Users\DTOs\CreateUserDTO;
use App\Application\Users\DTOs\UpdateUserDTO;
use App\Application\Users\Exceptions\UserNotFoundException;
use App\Application\Users\Services\UserService;
use App\Infrastructure\Persistence\User\InMemoryUserRepository;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for UserService.
 */
class UserServiceTest extends TestCase
{
    /**
     * @var UserService The service under test, backed by an in-memory repository.
     */
    private UserService $userService;

    /**
     * Initialises a fresh UserService instance backed by an in-memory repository before each test.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $userRepository = new InMemoryUserRepository();
        $this->userService = new UserService($userRepository);
    }

    /**
     * Asserts that all seeded users are returned and the first entry has the expected data.
     */
    public function testReturnsAllUsersWhenUsersExist(): void
    {
        $users = $this->userService->all();

        $this->assertIsArray($users);
        $this->assertCount(5, $users);
        $this->assertSame('Bill', $users[0]->getFirstName());
        $this->assertSame('Gates', $users[0]->getLastName());
    }

    /**
     * Asserts that a User entity with the correct data is returned after a successful creation.
     */
    public function testCreatesUserWhenValidDataIsProvided(): void
    {
        $dto = new CreateUserDTO(
            firstName: 'Jane',
            lastName: 'Doe',
            email: 'jane@example.com'
        );

        $user = $this->userService->create($dto);

        $this->assertSame('Jane', $user->getFirstName());
        $this->assertSame('Doe', $user->getLastName());
        $this->assertSame('jane@example.com', $user->getEmail());
        $this->assertIsInt($user->getId());
    }

    /**
     * Asserts that the correct User entity is returned when searching by an existing ID.
     */
    public function testReturnsUserWhenUserExists(): void
    {
        $user = $this->userService->find(1);

        $this->assertSame(1, $user->getId());
        $this->assertSame('Bill', $user->getFirstName());
        $this->assertSame('Gates', $user->getLastName());
        $this->assertSame('billgates@example.com', $user->getEmail());
    }

    /**
     * Asserts that UserNotFoundException is thrown when searching for a non-existent user ID.
     */
    public function testThrowsUserNotFoundExceptionWhenUserDoesNotExist(): void
    {
        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage('User with ID 999 not found.');

        $this->userService->find(999);
    }

    /**
     * Asserts that all provided fields are applied and the updated User entity is returned.
     */
    public function testUpdatesUserWhenUserExists(): void
    {
        $dto = new UpdateUserDTO(
            id: 1,
            firstName: 'William',
            lastName: 'Gates III',
            email: 'william.gates@example.com'
        );

        $user = $this->userService->update($dto);

        $this->assertSame(1, $user->getId());
        $this->assertSame('William', $user->getFirstName());
        $this->assertSame('Gates III', $user->getLastName());
        $this->assertSame('william.gates@example.com', $user->getEmail());
    }

    /**
     * Asserts that fields omitted from the update DTO retain their original values.
     */
    public function testPreservesUnchangedFieldsWhenPartialDataIsProvided(): void
    {
        $dto = new UpdateUserDTO(
            id: 2,
            firstName: 'Stephen'
        );

        $user = $this->userService->update($dto);

        $this->assertSame(2, $user->getId());
        $this->assertSame('Stephen', $user->getFirstName());
        $this->assertSame('Jobs', $user->getLastName());
        $this->assertSame('stevejobs@example.com', $user->getEmail());
    }

    /**
     * Asserts that UserNotFoundException is thrown when attempting to update a non-existent user.
     */
    public function testThrowsUserNotFoundExceptionWhenUpdatingNonExistentUser(): void
    {
        $dto = new UpdateUserDTO(
            id: 999,
            firstName: 'Test'
        );

        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage('User with ID 999 not found.');

        $this->userService->update($dto);
    }

    /**
     * Asserts that the delete operation returns true and the user can no longer be found.
     */
    public function testDeletesUserWhenUserExists(): void
    {
        $result = $this->userService->delete(3);

        $this->assertTrue($result);

        $this->expectException(UserNotFoundException::class);
        $this->userService->find(3);
    }

    /**
     * Asserts that the delete operation returns false when the ID does not match any stored user.
     */
    public function testReturnsFalseWhenUserDoesNotExist(): void
    {
        $result = $this->userService->delete(999);

        $this->assertFalse($result);
    }

    /**
     * Asserts that the total user count increases by one after a successful creation.
     */
    public function testIncreasesUserCountWhenUserIsCreated(): void
    {
        $initialCount = count($this->userService->all());

        $dto = new CreateUserDTO(
            firstName: 'New',
            lastName: 'User',
            email: 'new@example.com'
        );

        $this->userService->create($dto);

        $finalCount = count($this->userService->all());

        $this->assertSame($initialCount + 1, $finalCount);
    }

    /**
     * Asserts that the total user count decreases by one after a successful deletion.
     */
    public function testDecreasesUserCountWhenUserIsDeleted(): void
    {
        $initialCount = count($this->userService->all());

        $this->userService->delete(1);

        $finalCount = count($this->userService->all());

        $this->assertSame($initialCount - 1, $finalCount);
    }
}
