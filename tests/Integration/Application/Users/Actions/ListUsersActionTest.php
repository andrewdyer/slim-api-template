<?php

declare(strict_types=1);

namespace Tests\Integration\Application\Users\Actions;

use Tests\Integration\Application\Users\AbstractUsersTestCase;

/**
 * Integration tests for ListUsersAction.
 */
final class ListUsersActionTest extends AbstractUsersTestCase
{
    /**
     * Asserts that a 200 response containing all seeded users is returned.
     */
    public function testReturns200WithAllUsersWhenRequested(): void
    {
        $firstUser = $this->userFactory->create();
        $secondUser = $this->userFactory->create();

        $response = $this->request('GET', '/api/v1/users');

        $this->assertSame(200, $response->getStatusCode());

        $body = $this->decodeJson($response);

        $this->assertArrayHasKey('data', $body);

        $data = $body['data'];

        $emails = array_column($data, 'email');

        $this->assertContains($firstUser->getEmail(), $emails);
        $this->assertContains($secondUser->getEmail(), $emails);
    }
}
