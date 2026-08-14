<?php

declare(strict_types=1);

namespace Tests\Integration\Application\Actions;

use App\Application\Actions\ListUsersAction;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * Integration tests for ListUsersAction.
 */
#[CoversClass(ListUsersAction::class)]
final class ListUsersActionTest extends AbstractUserActionTestCase
{
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

    /**
     * Asserts that an invalid page parameter is clamped to a valid value.
     *
     * @param string $query        The query string under test.
     * @param int    $expectedPage The expected clamped page number.
     */
    #[DataProvider('invalidPageProvider')]
    public function testInvalidPageIsClamped(string $query, int $expectedPage): void
    {
        $response = $this->request('GET', "/api/v1/users?{$query}");

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($expectedPage, $this->decodeJson($response)['meta']['page']);
    }

    /**
     * Asserts that an invalid perPage parameter is clamped to a valid value.
     *
     * @param string $query           The query string under test.
     * @param int    $expectedPerPage The expected clamped page size.
     */
    #[DataProvider('invalidPerPageProvider')]
    public function testInvalidPerPageIsClamped(string $query, int $expectedPerPage): void
    {
        $response = $this->request('GET', "/api/v1/users?{$query}");

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($expectedPerPage, $this->decodeJson($response)['meta']['perPage']);
    }

    /**
     * Asserts that pagination metadata has the expected structure and consistent totals.
     */
    public function testPaginationMetadataIsCorrect(): void
    {
        $response = $this->request('GET', '/api/v1/users?page=1&perPage=5');

        $this->assertSame(200, $response->getStatusCode());

        $meta = $this->decodeJson($response)['meta'];

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
     * Asserts that the total user count increases after new users are created.
     */
    public function testTotalIncreasesAfterUsersAreCreated(): void
    {
        $beforeTotal = $this->decodeJson($this->request('GET', '/api/v1/users'))['meta']['total'];

        $this->userFactory->create();
        $this->userFactory->create();

        $afterTotal = $this->decodeJson($this->request('GET', '/api/v1/users'))['meta']['total'];

        $this->assertSame($beforeTotal + 2, $afterTotal);
    }
}
