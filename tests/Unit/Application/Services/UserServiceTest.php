<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Services;

use App\Application\DTOs\Inputs\CreateUserInput;
use App\Application\DTOs\Inputs\UpdateUserInput;
use App\Application\Exceptions\UserEmailAlreadyExistsException;
use App\Application\Exceptions\UserNotFoundException;
use App\Application\Services\UserService;
use App\Domain\Models\User;
use App\Domain\Repositories\UserRepositoryInterface;
use LogicException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tests\Support\Persistence\Repositories\InMemoryUserRepository;

/**
 * Unit tests for UserService.
 */
#[CoversClass(UserService::class)]
#[CoversClass(User::class)]
final class UserServiceTest extends TestCase
{
    /**
     * The service under test, backed by an in-memory repository.
     */
    private UserService $userService;

    /**
     * Creates a repository whose update() always returns null, regardless of findById().
     *
     * Simulates the user being removed between the existence check in
     * UserService::find() and the update() call moments later, a state a
     * single, well-behaved repository can't otherwise produce synchronously.
     *
     * @return UserRepositoryInterface The inconsistent test repository.
     * @internal
     */
    private function repositoryThatLosesTheUserDuringUpdate(): UserRepositoryInterface
    {
        return new class () implements UserRepositoryInterface {
            public function create(string $firstName, string $lastName, string $email): User
            {
                throw new LogicException('Not implemented.');
            }

            public function delete(int $id): bool
            {
                throw new LogicException('Not implemented.');
            }

            public function findAll(): array
            {
                throw new LogicException('Not implemented.');
            }

            public function findById(int $id): ?User
            {
                return new User($id, 'Ghost', 'User', 'ghost@example.com');
            }

            public function findPaginated(int $page, int $perPage): array
            {
                throw new LogicException('Not implemented.');
            }

            public function update(int $id, ?string $firstName, ?string $lastName, ?string $email): ?User
            {
                return null;
            }
        };
    }

    /**
     * Builds a fresh UserService backed by an in-memory repository before each test.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $userRepository = new InMemoryUserRepository();
        $this->userService = new UserService($userRepository);
    }

    /**
     * Asserts that a User entity with the correct data is returned after a successful creation.
     */
    public function testCreatesUserWhenValidDataIsProvided(): void
    {
        $input = new CreateUserInput(
            firstName: 'Jane',
            lastName: 'Doe',
            email: 'jane@example.com'
        );

        $user = $this->userService->create($input);

        $this->assertSame('Jane', $user->getFirstName());
        $this->assertSame('Doe', $user->getLastName());
        $this->assertSame('jane@example.com', $user->getEmail());
        $this->assertIsInt($user->getId());
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

    /**
     * Asserts that the user can no longer be found after a successful deletion.
     */
    public function testDeletesUserWhenUserExists(): void
    {
        $this->userService->delete(3);

        $this->expectException(UserNotFoundException::class);
        $this->userService->find(3);
    }

    /**
     * Asserts that the total user count increases by one after a successful creation.
     */
    public function testIncreasesUserCountWhenUserIsCreated(): void
    {
        $initialCount = count($this->userService->all());

        $input = new CreateUserInput(
            firstName: 'New',
            lastName: 'User',
            email: 'new@example.com'
        );

        $this->userService->create($input);

        $finalCount = count($this->userService->all());

        $this->assertSame($initialCount + 1, $finalCount);
    }

    /**
     * Asserts that fields omitted from the update input retain their original values.
     */
    public function testPreservesUnchangedFieldsWhenPartialDataIsProvided(): void
    {
        $input = new UpdateUserInput(
            id: 2,
            firstName: 'Stephen'
        );

        $user = $this->userService->update($input);

        $this->assertSame(2, $user->getId());
        $this->assertSame('Stephen', $user->getFirstName());
        $this->assertSame('Anderson', $user->getLastName());
        $this->assertSame('charlotte.anderson@example.com', $user->getEmail());
    }

    /**
     * Asserts that paginated results respect the perPage parameter.
     */
    public function testRespectsPerPageParameter(): void
    {
        $result = $this->userService->paginated(1, 3);

        $this->assertCount(3, $result['users']);
        $this->assertSame(5, $result['total']);
    }

    /**
     * Asserts that all seeded users are returned and the first entry has the expected data.
     */
    public function testReturnsAllUsersWhenUsersExist(): void
    {
        $users = $this->userService->all();

        $this->assertIsArray($users);
        $this->assertCount(5, $users);
        $this->assertSame('Oliver', $users[0]->getFirstName());
        $this->assertSame('French', $users[0]->getLastName());
    }

    /**
     * Asserts that the second page of paginated results contains different users.
     */
    public function testReturnsCorrectUsersForSecondPage(): void
    {
        $firstPageResult = $this->userService->paginated(1, 2);
        $secondPageResult = $this->userService->paginated(2, 2);

        $this->assertCount(2, $secondPageResult['users']);
        $this->assertNotSame(
            $firstPageResult['users'][0]->getId(),
            $secondPageResult['users'][0]->getId()
        );
    }

    /**
     * Asserts that an empty array is returned when requesting a page beyond available data.
     */
    public function testReturnsEmptyArrayWhenPageExceedsTotalPages(): void
    {
        $result = $this->userService->paginated(10, 10);

        $this->assertCount(0, $result['users']);
        $this->assertSame(5, $result['total']);
    }

    /**
     * Asserts that paginated results contain the correct number of users.
     */
    public function testReturnsPaginatedUsersWithCorrectCount(): void
    {
        $result = $this->userService->paginated(1, 2);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('users', $result);
        $this->assertArrayHasKey('total', $result);
        $this->assertCount(2, $result['users']);
        $this->assertSame(5, $result['total']);
    }

    /**
     * Asserts that the final page returns the remaining partial set of users.
     */
    public function testReturnsPartialFinalPage(): void
    {
        $result = $this->userService->paginated(2, 3);

        $this->assertCount(2, $result['users']);
        $this->assertSame(5, $result['total']);
        $this->assertSame(4, $result['users'][0]->getId());
        $this->assertSame(5, $result['users'][1]->getId());
    }

    /**
     * Asserts that the correct User entity is returned when searching by an existing ID.
     */
    public function testReturnsUserWhenUserExists(): void
    {
        $user = $this->userService->find(1);

        $this->assertSame(1, $user->getId());
        $this->assertSame('Oliver', $user->getFirstName());
        $this->assertSame('French', $user->getLastName());
        $this->assertSame('oliver.french@example.com', $user->getEmail());
    }

    /**
     * Asserts that UserEmailAlreadyExistsException is thrown when creating a user with an email already in use.
     */
    public function testThrowsUserEmailAlreadyExistsExceptionWhenCreatingWithDuplicateEmail(): void
    {
        $input = new CreateUserInput(
            firstName: 'Jane',
            lastName: 'Doe',
            email: 'oliver.french@example.com'
        );

        $this->expectException(UserEmailAlreadyExistsException::class);

        $this->userService->create($input);
    }

    /**
     * Asserts that UserEmailAlreadyExistsException is thrown when updating a user with an email already in use.
     */
    public function testThrowsUserEmailAlreadyExistsExceptionWhenUpdatingWithDuplicateEmail(): void
    {
        $input = new UpdateUserInput(
            id: 1,
            email: 'charlotte.anderson@example.com'
        );

        $this->expectException(UserEmailAlreadyExistsException::class);

        $this->userService->update($input);
    }

    /**
     * Asserts that UserNotFoundException is thrown when deleting a non-existent user.
     */
    public function testThrowsUserNotFoundExceptionWhenDeletingNonExistentUser(): void
    {
        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage('User with ID 999 not found.');

        $this->userService->delete(999);
    }

    /**
     * Asserts that UserNotFoundException is thrown when the repository reports no user was updated,
     * despite the same ID resolving to a user moments earlier.
     */
    public function testThrowsUserNotFoundExceptionWhenRepositoryReportsNoUserWasUpdated(): void
    {
        $userService = new UserService($this->repositoryThatLosesTheUserDuringUpdate());

        $input = new UpdateUserInput(
            id: 1,
            firstName: 'Test'
        );

        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage('User with ID 1 not found.');

        $userService->update($input);
    }

    /**
     * Asserts that UserNotFoundException is thrown when attempting to update a non-existent user.
     */
    public function testThrowsUserNotFoundExceptionWhenUpdatingNonExistentUser(): void
    {
        $input = new UpdateUserInput(
            id: 999,
            firstName: 'Test'
        );

        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage('User with ID 999 not found.');

        $this->userService->update($input);
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
        $input = new UpdateUserInput(
            id: 1,
            firstName: 'William',
            lastName: 'French III',
            email: 'william.gates@example.com'
        );

        $user = $this->userService->update($input);

        $this->assertSame(1, $user->getId());
        $this->assertSame('William', $user->getFirstName());
        $this->assertSame('French III', $user->getLastName());
        $this->assertSame('william.gates@example.com', $user->getEmail());
    }
}
