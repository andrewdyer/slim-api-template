<?php

declare(strict_types=1);

namespace Tests\Integration\Application\Users\Actions;

use App\Domain\User\UserRepository;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Tests\Integration\AbstractIntegrationTestCase;
use Tests\Support\Factories\UserFactory;

/**
 * Integration tests for UpdateUserAction.
 */
final class UpdateUserActionTest extends AbstractIntegrationTestCase
{
    /**
     * The user repository instance.
     */
    private UserRepository $users;

    /**
     * The user factory instance.
     */
    private UserFactory $user;

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

        $this->user = new UserFactory(
            $this->users,
            $this->faker
        );
    }

    /**
     * Asserts that a 200 response containing the updated user data is returned when the user exists.
     */
    public function testReturns200WithUpdatedUserWhenUserExists(): void
    {
        $user = $this->user->create();

        $updatedFirstName = $this->faker->firstName();
        $updatedLastName = $this->faker->lastName();
        $updatedEmail = $this->faker->unique()->safeEmail();

        $response = $this->request('PUT', '/api/v1/users/' . $user->getId(), [
            'first_name' => $updatedFirstName,
            'last_name' => $updatedLastName,
            'email' => $updatedEmail,
        ]);

        $this->assertSame(200, $response->getStatusCode());

        $body = $this->decodeJson($response);

        $this->assertArrayHasKey('data', $body);

        $data = $body['data'];
        $this->assertSame($user->getId(), $data['id']);
        $this->assertSame($updatedFirstName, $data['firstName']);
        $this->assertSame($updatedLastName, $data['lastName']);
        $this->assertSame($updatedEmail, $data['email']);
    }

    /**
     * Asserts that a 404 response is returned when no user exists with the given ID.
     */
    public function testReturns404WhenUserNotFound(): void
    {
        $user = $this->user->create();

        $this->users->delete($user->getId());

        $response = $this->request('PUT', '/api/v1/users/' . $user->getId(), [
            'first_name' => 'Ghost',
        ]);

        $this->assertSame(404, $response->getStatusCode());
    }
}
