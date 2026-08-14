<?php

declare(strict_types=1);

namespace Tests\Integration\Infrastructure\Persistence\Repositories;

use App\Infrastructure\Persistence\Models\EloquentPermissionModel;
use App\Infrastructure\Persistence\Models\EloquentRoleModel;
use App\Infrastructure\Persistence\Models\EloquentUserModel;
use App\Infrastructure\Persistence\Repositories\EloquentPermissionRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Integration\AbstractTestCase;

/**
 * Integration tests for EloquentPermissionRepository.
 */
#[CoversClass(EloquentPermissionRepository::class)]
#[CoversClass(EloquentUserModel::class)]
#[CoversClass(EloquentRoleModel::class)]
final class EloquentPermissionRepositoryTest extends AbstractTestCase
{
    /**
     * The repository under test.
     */
    private EloquentPermissionRepository $permissionRepository;

    /**
     * Creates a permission with a unique name for use in a test.
     *
     * @return EloquentPermissionModel The newly created permission.
     * @internal
     */
    private function createPermission(): EloquentPermissionModel
    {
        return EloquentPermissionModel::create([
            'name' => 'permission-' . $this->faker->unique()->numerify('######'),
            'description' => $this->faker->sentence(),
        ]);
    }

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
     * Builds a fresh EloquentPermissionRepository before each test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->permissionRepository = new EloquentPermissionRepository();
    }

    /**
     * Asserts that permission names shared across multiple assigned roles are returned only once.
     */
    public function testDeduplicatesPermissionNamesSharedAcrossRoles(): void
    {
        $user = $this->createUser();
        $shared = $this->createPermission();

        $roleA = $this->createRole();
        $roleB = $this->createRole();
        $roleA->permissions()->attach($shared->id);
        $roleB->permissions()->attach($shared->id);

        $user->roles()->attach([$roleA->id, $roleB->id]);

        $this->assertSame([$shared->name], $this->permissionRepository->findNamesForUser($user->id));
    }

    /**
     * Asserts that an empty array is returned for a user that does not exist.
     */
    public function testReturnsEmptyArrayWhenUserDoesNotExist(): void
    {
        $this->assertSame([], $this->permissionRepository->findNamesForUser(999999999));
    }

    /**
     * Asserts that an empty array is returned for a user with no assigned roles.
     */
    public function testReturnsEmptyArrayWhenUserHasNoRoles(): void
    {
        $user = $this->createUser();

        $this->assertSame([], $this->permissionRepository->findNamesForUser($user->id));
    }

    /**
     * Asserts that the names of permissions granted through the user's assigned role are returned.
     */
    public function testReturnsPermissionNamesGrantedThroughAssignedRole(): void
    {
        $user = $this->createUser();
        $role = $this->createRole();
        $permissionA = $this->createPermission();
        $permissionB = $this->createPermission();

        $role->permissions()->attach([$permissionA->id, $permissionB->id]);
        $user->roles()->attach($role->id);

        $expected = [$permissionA->name, $permissionB->name];
        sort($expected);

        $this->assertSame($expected, $this->permissionRepository->findNamesForUser($user->id));
    }
}
