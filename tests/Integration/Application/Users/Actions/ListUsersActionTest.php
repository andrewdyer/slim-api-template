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

        $emails = [];
        foreach ($data as $user) {
            $emails[] = $user['email'];
        }

        $this->assertContains($firstUser->getEmail(), $emails);
        $this->assertContains($secondUser->getEmail(), $emails);
    }

    /**
     * Asserts that each user in the response contains the expected fields and values.
     */
    public function testReturnsExpectedUserStructureWhenUsersExist(): void
    {
        $user = $this->userFactory->create();

        $response = $this->request('GET', '/api/v1/users');

        $this->assertSame(200, $response->getStatusCode());

        $body = $this->decodeJson($response);

        $this->assertArrayHasKey('data', $body);

        $data = $body['data'];

        $returnedUser = null;

        foreach ($data as $item) {
            if ($item['id'] === $user->getId()) {
                $returnedUser = $item;
                break;
            }
        }

        $this->assertNotNull($returnedUser);

        $this->assertArrayHasKey('id', $returnedUser);
        $this->assertArrayHasKey('firstName', $returnedUser);
        $this->assertArrayHasKey('lastName', $returnedUser);
        $this->assertArrayHasKey('email', $returnedUser);

        $this->assertSame($user->getId(), $returnedUser['id']);
        $this->assertSame($user->getFirstName(), $returnedUser['firstName']);
        $this->assertSame($user->getLastName(), $returnedUser['lastName']);
        $this->assertSame($user->getEmail(), $returnedUser['email']);
    }
}
