<?php

namespace Tests\Unit\Application\Users\Services;

use App\Application\Users\DTO\CreateUserDTO;
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
}
