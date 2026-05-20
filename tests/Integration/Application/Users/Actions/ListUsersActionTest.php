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
     * Asserts that created users appear in the paginated response regardless
     * of how many records already exist in the backing store.
     */
    public function testCreatedUsersAppearInPaginatedResponse(): void
    {
        $firstUser = $this->userFactory->create();
        $secondUser = $this->userFactory->create();

        // Fetch the total count first, then request all records in one page.
        // This ensures the assertion holds whether the backing store is empty
        // (in-memory) or already contains data (e.g. Eloquent against a seeded DB).
        $countResponse = $this->request('GET', '/api/v1/users');
        $total = $this->decodeJson($countResponse)['data']['meta']['total'];

        $response = $this->request('GET', "/api/v1/users?page=1&perPage={$total}");
        $body = $this->decodeJson($response);
        $users = $body['data']['data'];

        $emails = array_column($users, 'email');
        $this->assertContains($firstUser->getEmail(), $emails);
        $this->assertContains($secondUser->getEmail(), $emails);
    }

    /**
     * Asserts that the pagination metadata is structurally correct and
     * that the totals and page count arithmetic is consistent.
     */
    public function testPaginationMetadataIsCorrect(): void
    {
        $response = $this->request('GET', '/api/v1/users?page=1&perPage=5');

        $this->assertSame(200, $response->getStatusCode());

        $meta = $this->decodeJson($response)['data']['meta'];

        $this->assertArrayHasKey('total', $meta);
        $this->assertArrayHasKey('page', $meta);
        $this->assertArrayHasKey('perPage', $meta);
        $this->assertArrayHasKey('totalPages', $meta);

        $this->assertSame(1, $meta['page']);
        $this->assertSame(5, $meta['perPage']);
        $this->assertSame(
            (int)ceil($meta['total'] / $meta['perPage']),
            $meta['totalPages']
        );
    }
}
