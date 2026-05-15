<?php

declare(strict_types=1);

namespace Tests\Integration\Application\Users\Actions;

use App\Domain\User\UserRepository;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Tests\Integration\AbstractIntegrationTestCase;
use Tests\Support\Factories\UserFactory;

/**
 * Integration tests for DeleteUserAction.
 */
final class DeleteUserActionTest extends AbstractIntegrationTestCase
{
    /**
     * The user repository instance.
     */
    private UserRepository $users;

    /**
     * The user factory instance.
     */
    private UserFactory $userFactory;

    /**
     * Sets up the test dependencies before each test.
     *
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->users = $this->app
            ->getContainer()
            ->get(UserRepository::class);

        $this->userFactory = new UserFactory(
            $this->users,
            $this->faker
        );
    }

    /**
     * Asserts that a 204 response with an empty body is returned when the user exists.
     */
    public function testReturns204WhenUserExists(): void
    {
        $user = $this->userFactory->create();

        $response = $this->request('DELETE', '/api/v1/users/' . $user->getId());

        $this->assertSame(204, $response->getStatusCode());
        $this->assertSame([], $this->decodeJson($response));
    }

    /**
     * Asserts that a 204 response is still returned even when the user does not exist.
     */
    public function testReturns204WhenUserDoesNotExist(): void
    {
        $user = $this->userFactory->create();

        $this->users->delete($user->getId());

        $response = $this->request('DELETE', '/api/v1/users/' . $user->getId());

        $this->assertSame(204, $response->getStatusCode());
    }
}
