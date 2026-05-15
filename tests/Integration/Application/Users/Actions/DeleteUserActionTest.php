<?php

declare(strict_types=1);

namespace Tests\Integration\Application\Users\Actions;

use Tests\Integration\Application\Users\AbstractUsersTestCase;

/**
 * Integration tests for DeleteUserAction.
 */
final class DeleteUserActionTest extends AbstractUsersTestCase
{

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

        $this->userRepository->delete($user->getId());

        $response = $this->request('DELETE', '/api/v1/users/' . $user->getId());

        $this->assertSame(204, $response->getStatusCode());
    }
}
