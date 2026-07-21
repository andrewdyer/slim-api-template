<?php

declare(strict_types=1);

namespace Tests\Integration\Application\Actions;

use App\Domain\Repositories\UserRepositoryInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Tests\Integration\AbstractTestCase;
use Tests\Support\Factories\UserFactory;

/**
 * Provides shared setup for user-related integration tests.
 */
abstract class AbstractUserActionTestCase extends AbstractTestCase
{
    /**
     * The user factory instance.
     */
    protected UserFactory $userFactory;
    /**
     * The user repository instance.
     */
    protected UserRepositoryInterface $userRepository;

    /**
     * Builds the user test dependencies before each test.
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = $this->app
            ->getContainer()
            ->get(UserRepositoryInterface::class);

        $this->userFactory = new UserFactory(
            $this->userRepository,
            $this->faker
        );
    }
}
