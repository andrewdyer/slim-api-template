<?php

namespace Tests\Unit\Application\Users\Services;

use App\Application\Users\DTO\CreateUserDTO;
use App\Application\Users\DTO\UpdateUserDTO;
use App\Application\Users\Exceptions\UserNotFoundException;
use App\Application\Users\Services\UserService;
use App\Domain\Users\Entities\User;
use App\Infrastructure\Persistence\Users\Repositories\InMemoryUserRepository;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for the UserService application service.
 *
 * This test class verifies that the UserService correctly handles
 * all CRUD operations for users, including proper exception handling
 * and interaction with the user repository.
 */
final class UserServiceTest extends TestCase
{
    /**
     * In-memory user repository for testing.
     */
    private InMemoryUserRepository $repository;

    /**
     * User service instance under test.
     */
    private UserService $service;

    /**
     * Set up test dependencies before each test method.
     *
     * Initializes the in-memory repository and user service for testing.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new InMemoryUserRepository();
        $this->service = new UserService($this->repository);
    }

    /**
     * Test that the all method returns all users from the repository.
     *
     * Verifies that the service correctly retrieves all users and returns
     * them as an array containing only User entity instances.
     *
     * @return void
     */
    public function testAllReturnsAllUsers(): void
    {
        $users = $this->service->all();

        $this->assertIsArray($users);
        $this->assertCount(5, $users);
        $this->assertContainsOnlyInstancesOf(User::class, $users);
    }

    /**
     * Test that creating a user generates a unique ID.
     *
     * Verifies that when a user is created, the service assigns a valid
     * positive integer ID to the new user entity.
     *
     * @return void
     */
    public function testCreateUserAddsUniqueId(): void
    {
        $dto = new CreateUserDTO(
            firstName: 'Bob',
            lastName: 'Smith',
            email: 'bob@example.com'
        );

        $user = $this->service->create($dto);

        $this->assertIsInt($user->getId());
        $this->assertGreaterThan(0, $user->getId());
    }

    /**
     * Test successful user creation with valid data.
     *
     * Verifies that when valid user data is provided via CreateUserDTO,
     * the service creates a new user with the correct properties and
     * persists it to the repository.
     *
     * @return void
     */
    public function testCreateUserSuccessfully(): void
    {
        $dto = new CreateUserDTO(
            firstName: 'Bob',
            lastName: 'Smith',
            email: 'bob@example.com'
        );

        $user = $this->service->create($dto);

        $this->assertInstanceOf(User::class, $user);
        $this->assertSame('Bob', $user->getFirstName());
        $this->assertSame('Smith', $user->getLastName());
        $this->assertSame('bob@example.com', $user->getEmail());

        $this->assertCount(6, $this->repository->findAll());
    }

    /**
     * Test that deleting a non-existent user returns false.
     *
     * Verifies that when attempting to delete a user that doesn't exist,
     * the service returns false and doesn't modify the repository.
     *
     * @return void
     */
    public function testDeleteNonExistentUserReturnsFalse(): void
    {
        $initialCount = count($this->repository->findAll());

        $result = $this->service->delete(999);

        $this->assertFalse($result);
        $this->assertCount($initialCount, $this->repository->findAll());
    }

    /**
     * Test successful user deletion.
     *
     * Verifies that when a valid user ID is provided, the service
     * successfully removes the user from the repository and returns true.
     *
     * @return void
     */
    public function testDeleteUserSuccessfully(): void
    {
        $initialCount = count($this->repository->findAll());

        $result = $this->service->delete(1);

        $this->assertTrue($result);
        $this->assertCount($initialCount - 1, $this->repository->findAll());
    }

    /**
     * Test successful user retrieval by ID.
     *
     * Verifies that when a valid user ID is provided, the service
     * returns the correct user entity with all expected properties.
     *
     * @return void
     */
    public function testFindUserSuccessfully(): void
    {
        $user = $this->service->find(1);

        $this->assertInstanceOf(User::class, $user);
        $this->assertSame(1, $user->getId());
        $this->assertSame('Bill', $user->getFirstName());
        $this->assertSame('Gates', $user->getLastName());
        $this->assertSame('billgates@example.com', $user->getEmail());
    }

    /**
     * Test that finding a non-existent user throws an exception.
     *
     * Verifies that when an invalid user ID is provided, the service
     * throws a UserNotFoundException with the appropriate message.
     *
     * @return void
     */
    public function testFindUserThrowsExceptionWhenNotFound(): void
    {
        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage('User with ID 999 not found.');

        $this->service->find(999);
    }

    /**
     * Test that updating a non-existent user throws an exception.
     *
     * Verifies that when attempting to update a user that doesn't exist,
     * the service throws a UserNotFoundException with the appropriate message.
     *
     * @return void
     */
    public function testUpdateNonExistentUserThrowsException(): void
    {
        $dto = new UpdateUserDTO(
            id: 999,
            firstName: 'Alice',
            lastName: 'Johnson',
            email: 'alice@example.com'
        );

        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage('User with ID 999 not found.');

        $this->service->update($dto);
    }

    /**
     * Test partial user update with only email field.
     *
     * Verifies that when only the email field is updated, other fields
     * retain their original values while the email is changed correctly.
     *
     * @return void
     */
    public function testUpdateUserPartiallyWithOnlyEmail(): void
    {
        $originalUser = $this->service->find(1);
        $dto = new UpdateUserDTO(
            id: 1,
            firstName: null,
            lastName: null,
            email: 'alice@example.com'
        );

        $updatedUser = $this->service->update($dto);

        $this->assertSame($originalUser->getFirstName(), $updatedUser->getFirstName());
        $this->assertSame($originalUser->getLastName(), $updatedUser->getLastName());
        $this->assertSame('alice@example.com', $updatedUser->getEmail());
    }

    /**
     * Test partial user update with only first name field.
     *
     * Verifies that when only the first name field is updated, other fields
     * retain their original values while the first name is changed correctly.
     *
     * @return void
     */
    public function testUpdateUserPartiallyWithOnlyFirstName(): void
    {
        $originalUser = $this->service->find(1);
        $dto = new UpdateUserDTO(
            id: 1,
            firstName: 'Alice',
            lastName: null,
            email: null
        );

        $updatedUser = $this->service->update($dto);

        $this->assertSame('Alice', $updatedUser->getFirstName());
        $this->assertSame($originalUser->getLastName(), $updatedUser->getLastName());
        $this->assertSame($originalUser->getEmail(), $updatedUser->getEmail());
    }

    /**
     * Test successful user update with all fields.
     *
     * Verifies that when all user fields are updated via UpdateUserDTO,
     * the service correctly applies all changes and returns the updated entity.
     *
     * @return void
     */
    public function testUpdateUserSuccessfully(): void
    {
        $dto = new UpdateUserDTO(
            id: 1,
            firstName: 'Alice',
            lastName: 'Johnson',
            email: 'alice@example.com'
        );

        $updatedUser = $this->service->update($dto);

        $this->assertInstanceOf(User::class, $updatedUser);
        $this->assertSame(1, $updatedUser->getId());
        $this->assertSame('Alice', $updatedUser->getFirstName());
        $this->assertSame('Johnson', $updatedUser->getLastName());
        $this->assertSame('alice@example.com', $updatedUser->getEmail());
    }
}
