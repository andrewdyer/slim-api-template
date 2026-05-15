<?php

declare(strict_types=1);

namespace Tests\Integration\Application\Users\Actions;

use Tests\Integration\Application\Users\AbstractUsersTestCase;

/**
 * Integration tests for UpdateUserAction.
 */
final class UpdateUserActionTest extends AbstractUsersTestCase
{
    /**
     * Asserts that a 200 response containing the updated user data is returned when the user exists.
     */
    public function testReturns200WithUpdatedUserWhenUserExists(): void
    {
        $user = $this->userFactory->create();

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
        $user = $this->userFactory->create();

        $this->userRepository->delete($user->getId());

        $response = $this->request('PUT', '/api/v1/users/' . $user->getId(), [
            'first_name' => 'Ghost',
        ]);

        $this->assertSame(404, $response->getStatusCode());
    }
}
