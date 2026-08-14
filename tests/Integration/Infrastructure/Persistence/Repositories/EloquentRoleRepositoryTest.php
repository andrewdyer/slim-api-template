<?php

declare(strict_types=1);

namespace Tests\Integration\Infrastructure\Persistence\Repositories;

use App\Infrastructure\Persistence\Models\EloquentRoleModel;
use App\Infrastructure\Persistence\Models\EloquentUserModel;
use App\Infrastructure\Persistence\Repositories\EloquentRoleRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Integration\AbstractTestCase;

/**
 * Integration tests for EloquentRoleRepository.
 */
#[CoversClass(EloquentRoleRepository::class)]
#[CoversClass(EloquentUserModel::class)]
final class EloquentRoleRepositoryTest extends AbstractTestCase
{
    /**
     * The repository under test.
     */
    private EloquentRoleRepository $roleRepository;

    /**
     * Creates a role with a unique name for use in a test.
     *
     * @return EloquentRoleModel The newly created role.
     * @internal
     */
    private function createRole(): EloquentRoleModel
    {
        return EloquentRoleModel::create([
            'name' => 'role-' . $this->faker->unique()->numerify('######'),
            'description' => $this->faker->sentence(),
        ]);
    }

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
     * Builds a fresh EloquentRoleRepository before each test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->roleRepository = new EloquentRoleRepository();
    }

    /**
     * Asserts that the names of a user's assigned roles are returned in alphabetical order.
     */
    public function testReturnsAssignedRoleNamesInAlphabeticalOrder(): void
    {
        $user = $this->createUser();
        $roleA = $this->createRole();
        $roleB = $this->createRole();

        $user->roles()->attach([$roleA->id, $roleB->id]);

        $expected = [$roleA->name, $roleB->name];
        sort($expected);

        $this->assertSame($expected, $this->roleRepository->findNamesForUser($user->id));
    }

    /**
     * Asserts that an empty array is returned for a user that does not exist.
     */
    public function testReturnsEmptyArrayWhenUserDoesNotExist(): void
    {
        $this->assertSame([], $this->roleRepository->findNamesForUser(999999999));
    }

    /**
     * Asserts that an empty array is returned for a user with no assigned roles.
     */
    public function testReturnsEmptyArrayWhenUserHasNoRoles(): void
    {
        $user = $this->createUser();

        $this->assertSame([], $this->roleRepository->findNamesForUser($user->id));
    }
}
