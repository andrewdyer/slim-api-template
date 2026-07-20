<?php

declare(strict_types=1);

namespace Integration\Application\Actions;

use Integration\Application\AbstractUsersTestCase;

/**
 * Integration tests for DeleteUserAction.
 */
final class DeleteUserActionTest extends AbstractUsersTestCase
{
    /**
     * Asserts that a 204 response is returned when the user exists.
     */
    public function testReturns204WhenUserExists(): void
    {
        $user = $this->userFactory->create();

        $response = $this->request('DELETE', '/api/v1/users/' . $user->getId());

        $this->assertSame(204, $response->getStatusCode());
        $this->assertNull($this->userRepository->findById($user->getId()));
    }

    /**
     * Asserts that a 404 response is returned when the user does not exist.
     */
    public function testReturns404WhenUserDoesNotExist(): void
    {
        $user = $this->userFactory->create();

        $this->userRepository->delete($user->getId());

        $response = $this->request('DELETE', '/api/v1/users/' . $user->getId());

        $this->assertSame(404, $response->getStatusCode());
    }
}
