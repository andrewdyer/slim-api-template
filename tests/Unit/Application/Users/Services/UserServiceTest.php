<?php

namespace Tests\Unit\Application\Users\Services;

use App\Application\Users\DTO\CreateUserDTO;
use App\Application\Users\DTO\UpdateUserDTO;
use App\Application\Users\Exceptions\UserNotFoundException;
use App\Application\Users\Services\UserService;
use App\Domain\Users\Entities\User;
use App\Infrastructure\Persistence\Users\Repositories\InMemoryUserRepository;
use PHPUnit\Framework\TestCase;

final class UserServiceTest extends TestCase
{
    private InMemoryUserRepository $repository;
    private UserService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new InMemoryUserRepository();
        $this->service = new UserService($this->repository);
    }

    public function testAllReturnsAllUsers(): void
    {
        $users = $this->service->all();

        $this->assertIsArray($users);
        $this->assertCount(2, $users);
        $this->assertContainsOnlyInstancesOf(User::class, $users);
    }

    public function testAllReturnsEmptyArrayWhenNoUsers(): void
    {
        $emptyRepository = new InMemoryUserRepository([]);
        $emptyService = new UserService($emptyRepository);

        $users = $emptyService->all();

        $this->assertIsArray($users);
        $this->assertEmpty($users);
    }

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

    public function testCreateUserSuccessfully(): void
    {
        $dto = new CreateUserDTO(
            firstName: 'Alice',
            lastName: 'Johnson',
            email: 'alice@example.com'
        );

        $user = $this->service->create($dto);

        $this->assertInstanceOf(User::class, $user);
        $this->assertSame('Alice', $user->getFirstName());
        $this->assertSame('Johnson', $user->getLastName());
        $this->assertSame('alice@example.com', $user->getEmail());

        $this->assertCount(3, $this->repository->findAll());
    }

    public function testDeleteNonExistentUserReturnsFalse(): void
    {
        $initialCount = count($this->repository->findAll());

        $result = $this->service->delete(999);

        $this->assertFalse($result);
        $this->assertCount($initialCount, $this->repository->findAll());
    }

    public function testDeleteUserSuccessfully(): void
    {
        $initialCount = count($this->repository->findAll());

        $result = $this->service->delete(1);

        $this->assertTrue($result);
        $this->assertCount($initialCount - 1, $this->repository->findAll());
    }

    public function testFindUserSuccessfully(): void
    {
        $user = $this->service->find(1);

        $this->assertInstanceOf(User::class, $user);
        $this->assertSame(1, $user->getId());
        $this->assertSame('John', $user->getFirstName());
        $this->assertSame('Doe', $user->getLastName());
        $this->assertSame('johndoe@example.com', $user->getEmail());
    }

    public function testFindUserThrowsExceptionWhenNotFound(): void
    {
        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage('User with ID 999 not found.');

        $this->service->find(999);
    }

    public function testUpdateNonExistentUserThrowsException(): void
    {
        $dto = new UpdateUserDTO(
            id: 999,
            firstName: 'Test',
            lastName: 'User',
            email: 'test@example.com'
        );

        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage('User with ID 999 not found.');

        $this->service->update($dto);
    }

    public function testUpdateUserPartiallyWithOnlyEmail(): void
    {
        $originalUser = $this->service->find(1);
        $dto = new UpdateUserDTO(
            id: 1,
            firstName: null,
            lastName: null,
            email: 'newemail@example.com'
        );

        $updatedUser = $this->service->update($dto);

        $this->assertSame($originalUser->getFirstName(), $updatedUser->getFirstName());
        $this->assertSame($originalUser->getLastName(), $updatedUser->getLastName());
        $this->assertSame('newemail@example.com', $updatedUser->getEmail());
    }

    public function testUpdateUserPartiallyWithOnlyFirstName(): void
    {
        $originalUser = $this->service->find(1);
        $dto = new UpdateUserDTO(
            id: 1,
            firstName: 'UpdatedName',
            lastName: null,
            email: null
        );

        $updatedUser = $this->service->update($dto);

        $this->assertSame('UpdatedName', $updatedUser->getFirstName());
        $this->assertSame($originalUser->getLastName(), $updatedUser->getLastName());
        $this->assertSame($originalUser->getEmail(), $updatedUser->getEmail());
    }

    public function testUpdateUserSuccessfully(): void
    {
        $dto = new UpdateUserDTO(
            id: 1,
            firstName: 'Jane',
            lastName: 'Smith',
            email: 'jane.smith@example.com'
        );

        $updatedUser = $this->service->update($dto);

        $this->assertInstanceOf(User::class, $updatedUser);
        $this->assertSame(1, $updatedUser->getId());
        $this->assertSame('Jane', $updatedUser->getFirstName());
        $this->assertSame('Smith', $updatedUser->getLastName());
        $this->assertSame('jane.smith@example.com', $updatedUser->getEmail());
    }
}
