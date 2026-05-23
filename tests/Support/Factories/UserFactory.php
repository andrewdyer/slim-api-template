<?php

declare(strict_types=1);

namespace Tests\Support\Factories;

use App\Domain\User\User;
use App\Domain\User\UserRepository;
use Faker\Generator;

/**
 * Factory for creating test User entities with generated or overridden data.
 */
final readonly class UserFactory
{
    /**
     * Creates a new UserFactory with the given repository and faker instance.
     *
     * @param UserRepository $users The repository used to persist created users.
     * @param Generator      $faker The Faker generator for creating random data.
     */
    public function __construct(
        private UserRepository $users,
        private Generator      $faker,
    ) {
    }

    /**
     * Creates a new user with generated data, allowing selective overrides.
     *
     * @param  array<string, mixed> $overrides Optional field overrides (firstName, lastName, email).
     * @return User                 The newly created User entity.
     */
    public function create(array $overrides = []): User
    {
        return $this->users->create(
            firstName: $overrides['firstName'] ?? $this->faker->firstName(),
            lastName: $overrides['lastName'] ?? $this->faker->lastName(),
            email: $overrides['email'] ?? $this->faker->unique()->safeEmail(),
        );
    }
}
