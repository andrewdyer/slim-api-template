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
        $this->assertCount(5, $users);
        $this->assertContainsOnlyInstancesOf(User::class, $users);
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
        $this->assertSame('Bill', $user->getFirstName());
        $this->assertSame('Gates', $user->getLastName());
        $this->assertSame('billgates@example.com', $user->getEmail());
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
            firstName: 'Alice',
            lastName: 'Johnson',
            email: 'alice@example.com'
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
            email: 'alice@example.com'
        );

        $updatedUser = $this->service->update($dto);

        $this->assertSame($originalUser->getFirstName(), $updatedUser->getFirstName());
        $this->assertSame($originalUser->getLastName(), $updatedUser->getLastName());
        $this->assertSame('alice@example.com', $updatedUser->getEmail());
    }

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
