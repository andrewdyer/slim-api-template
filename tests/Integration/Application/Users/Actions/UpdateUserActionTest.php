<?php

declare(strict_types=1);

namespace Tests\Integration\Application\Users\Actions;

use Tests\Integration\AbstractIntegrationTestCase;

/**
 * Integration tests for UpdateUserAction.
 */
class UpdateUserActionTest extends AbstractIntegrationTestCase
{
    /**
     * Asserts that a 200 response containing the updated user data is returned when the user exists.
     */
    public function testReturns200WithUpdatedUserWhenUserExists(): void
    {
        $response = $this->request('PUT', '/api/v1/users/1', [
            'first_name' => 'Ollie',
            'last_name' => 'Frenchie',
            'email' => 'ollie.frenchie@example.com',
        ]);

        $this->assertSame(200, $response->getStatusCode());

        $body = $this->decodeJson($response);

        $this->assertArrayHasKey('data', $body);

        $user = $body['data'];
        $this->assertSame(1, $user['id']);
        $this->assertSame('Ollie', $user['firstName']);
        $this->assertSame('Frenchie', $user['lastName']);
        $this->assertSame('ollie.frenchie@example.com', $user['email']);
    }

    /**
     * Asserts that a 500 response is returned when no user exists with the given ID.
     */
    public function testReturns500WhenUserNotFound(): void
    {
        $response = $this->request('PUT', '/api/v1/users/999', [
            'first_name' => 'Ghost',
        ]);

        $this->assertSame(500, $response->getStatusCode());
    }
}
