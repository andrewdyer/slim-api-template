<?php

declare(strict_types=1);

namespace Tests\Integration\Application\Users;

use App\Domain\User\UserRepository;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Tests\Integration\AbstractTestCase;
use Tests\Support\Factories\UserFactory;

/**
 * Base class for User-related integration tests.
 */
abstract class AbstractUsersTestCase extends AbstractTestCase
{
    /**
     * The user repository instance.
     */
    protected UserRepository $userRepository;

    /**
     * The user factory instance.
     */
    protected UserFactory $userFactory;

    /**
     * Sets up the test dependencies before each test.
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = $this->app
            ->getContainer()
            ->get(UserRepository::class);

        $this->userFactory = new UserFactory(
            $this->userRepository,
            $this->faker
        );
    }
}
