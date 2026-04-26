<?php

declare(strict_types=1);

namespace Tests\Integration\Application\Users\Actions;

use Tests\Integration\AbstractIntegrationTestCase;

/**
 * Integration tests for ListUsersAction.
 */
final class ListUsersActionTest extends AbstractIntegrationTestCase
{
    /**
     * Asserts that a 200 response containing all seeded users is returned.
     */
    public function testReturns200WithAllUsersWhenRequested(): void
    {
        $response = $this->request('GET', '/api/v1/users');

        $this->assertSame(200, $response->getStatusCode());

        $body = $this->decodeJson($response);

        $this->assertArrayHasKey('data', $body);
        $this->assertIsArray($body['data']);
        $this->assertCount(5, $body['data']);
    }

    /**
     * Asserts that each user in the response contains the expected fields and values.
     */
    public function testReturnsExpectedUserStructureWhenUsersExist(): void
    {
        $response = $this->request('GET', '/api/v1/users');
        $body = $this->decodeJson($response);

        $first = $body['data'][0];

        $this->assertArrayHasKey('id', $first);
        $this->assertArrayHasKey('firstName', $first);
        $this->assertArrayHasKey('lastName', $first);
        $this->assertArrayHasKey('email', $first);

        $this->assertSame(1, $first['id']);
        $this->assertSame('Oliver', $first['firstName']);
        $this->assertSame('French', $first['lastName']);
        $this->assertSame('oliver.french@example.com', $first['email']);
    }
}
