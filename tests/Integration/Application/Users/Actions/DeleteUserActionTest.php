<?php

declare(strict_types=1);

namespace Tests\Integration\Application\Users\Actions;

use Tests\Integration\AbstractIntegrationTestCase;

/**
 * Integration tests for DeleteUserAction.
 */
final class DeleteUserActionTest extends AbstractIntegrationTestCase
{
    /**
     * Asserts that a 204 response with an empty body is returned when the user exists.
     */
    public function testReturns204WhenUserExists(): void
    {
        $response = $this->request('DELETE', '/api/v1/users/1');

        $this->assertSame(204, $response->getStatusCode());
        $this->assertSame([], $this->decodeJson($response));
    }

    /**
     * Asserts that a 204 response is still returned even when the user does not exist.
     */
    public function testReturns204WhenUserDoesNotExist(): void
    {
        // DeleteUserAction does not throw on a missing ID — the repository
        // returns false silently and the action always responds 204.
        $response = $this->request('DELETE', '/api/v1/users/999');

        $this->assertSame(204, $response->getStatusCode());
    }
}
