<?php

declare(strict_types=1);

namespace Tests\Integration\Application\Users\Actions;

use Tests\Integration\AbstractIntegrationTestCase;

/**
 * Integration tests for ShowUserAction.
 */
class ShowUserActionTest extends AbstractIntegrationTestCase
{
    /**
     * Asserts that a 200 response containing the requested user is returned when the user exists.
     */
    public function testReturns200WithUserWhenUserExists(): void
    {
        $response = $this->request('GET', '/api/v1/users/1');

        $this->assertSame(200, $response->getStatusCode());

        $body = $this->decodeJson($response);

        $this->assertArrayHasKey('data', $body);

        $user = $body['data'];
        $this->assertSame(1, $user['id']);
        $this->assertSame('Oliver', $user['firstName']);
        $this->assertSame('French', $user['lastName']);
        $this->assertSame('oliver.french@example.com', $user['email']);
    }

    /**
     * Asserts that a 500 response is returned when no user exists with the given ID.
     */
    public function testReturns500WhenUserNotFound(): void
    {
        $response = $this->request('GET', '/api/v1/users/999');

        $this->assertSame(500, $response->getStatusCode());
    }
}
