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
        $this->assertSame(200, $countResponse->getStatusCode());
        $total = $this->decodeJson($countResponse)['data']['meta']['total'];

        $response = $this->request('GET', "/api/v1/users?page=1&perPage={$total}");
        $this->assertSame(200, $response->getStatusCode());
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

    /**
     * Asserts that an invalid page parameter is clamped to a valid value.
     *
     * @dataProvider invalidPageProvider
     */
    public function testInvalidPageIsClamped(string $query, int $expectedPage): void
    {
        $response = $this->request('GET', "/api/v1/users?{$query}");

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($expectedPage, $this->decodeJson($response)['data']['meta']['page']);
    }

    /**
     * Provides invalid page query strings and their expected clamped values.
     *
     * @return array<string, array{string, int}>
     */
    public static function invalidPageProvider(): array
    {
        return [
            'zero page' => ['page=0&perPage=10', 1],
            'negative page' => ['page=-5&perPage=10', 1],
            'non-numeric' => ['page=abc&perPage=10', 1],
        ];
    }

    /**
     * Asserts that an invalid perPage parameter is clamped to a valid value.
     *
     * @dataProvider invalidPerPageProvider
     */
    public function testInvalidPerPageIsClamped(string $query, int $expectedPerPage): void
    {
        $response = $this->request('GET', "/api/v1/users?{$query}");

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($expectedPerPage, $this->decodeJson($response)['data']['meta']['perPage']);
    }

    /**
     * Provides invalid perPage query strings and their expected clamped values.
     *
     * @return array<string, array{string, int}>
     */
    public static function invalidPerPageProvider(): array
    {
        return [
            'zero perPage' => ['page=1&perPage=0', 1],
            'negative perPage' => ['page=1&perPage=-10', 1],
            'exceeds maximum' => ['page=1&perPage=999', 100],
        ];
    }
}
