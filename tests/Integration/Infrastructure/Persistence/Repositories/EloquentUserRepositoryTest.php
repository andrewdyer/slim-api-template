<?php

declare(strict_types=1);

namespace Tests\Integration\Infrastructure\Persistence\Repositories;

use App\Infrastructure\Persistence\Models\EloquentUserModel;
use App\Infrastructure\Persistence\Repositories\EloquentUserRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Integration\AbstractTestCase;

/**
 * Integration tests for EloquentUserRepository.
 *
 * Covers repository paths that UserService never reaches, since it always
 * confirms a user exists via findById() before calling delete() or update().
 */
#[CoversClass(EloquentUserRepository::class)]
final class EloquentUserRepositoryTest extends AbstractTestCase
{
    /**
     * The repository under test.
     */
    private EloquentUserRepository $userRepository;

    /**
     * Creates a user for use in a test.
     *
     * @return EloquentUserModel The newly created user.
     * @internal
     */
    private function createUser(): EloquentUserModel
    {
        return EloquentUserModel::create([
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
        ]);
    }

    /**
     * Builds a fresh EloquentUserRepository before each test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = new EloquentUserRepository();
    }

    /**
     * Asserts that false is returned when deleting a user that does not exist.
     */
    public function testDeleteReturnsFalseWhenUserDoesNotExist(): void
    {
        $this->assertFalse($this->userRepository->delete(999999999));
    }

    /**
     * Asserts that all persisted users are included in the result.
     */
    public function testFindAllReturnsAllPersistedUsers(): void
    {
        $userA = $this->createUser();
        $userB = $this->createUser();

        $ids = array_map(static fn ($user) => $user->getId(), $this->userRepository->findAll());

        $this->assertContains($userA->id, $ids);
        $this->assertContains($userB->id, $ids);
    }

    /**
     * Asserts that null is returned when updating a user that does not exist.
     */
    public function testUpdateReturnsNullWhenUserDoesNotExist(): void
    {
        $this->assertNull($this->userRepository->update(999999999, 'Ghost', 'User', null));
    }
}
