<?php

declare(strict_types=1);

namespace Tests\Integration\Application\Users\Actions;

use Tests\Integration\Application\Users\AbstractUsersTestCase;

/**
 * Integration tests for ShowUserAction.
 */
final class ShowUserActionTest extends AbstractUsersTestCase
{
    /**
     * Asserts that a 200 response containing the requested user is returned when the user exists.
     */
    public function testReturns200WithUserWhenUserExists(): void
    {
        $user = $this->userFactory->create();

        $response = $this->request('GET', '/api/v1/users/' . $user->getId());

        $this->assertSame(200, $response->getStatusCode());

        $body = $this->decodeJson($response);

        $this->assertArrayHasKey('data', $body);

        $data = $body['data'];
        $this->assertSame($user->getId(), $data['id']);
        $this->assertSame($user->getFirstName(), $data['firstName']);
        $this->assertSame($user->getLastName(), $data['lastName']);
        $this->assertSame($user->getEmail(), $data['email']);
    }

    /**
     * Asserts that a 404 response is returned when no user exists with the given ID.
     */
    public function testReturns404WhenUserNotFound(): void
    {
        $user = $this->userFactory->create();

        $this->userRepository->delete($user->getId());

        $response = $this->request('GET', '/api/v1/users/' . $user->getId());

        $this->assertSame(404, $response->getStatusCode());
    }
}
