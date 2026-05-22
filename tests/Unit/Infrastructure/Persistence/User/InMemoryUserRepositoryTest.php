<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Persistence\User;

use App\Infrastructure\Persistence\User\InMemoryUserRepository;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for InMemoryUserRepository.
 */
final class InMemoryUserRepositoryTest extends TestCase
{
    /**
     * The repository instance under test.
     */
    private InMemoryUserRepository $repository;

    /**
     * Initializes a fresh repository instance before each test.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new InMemoryUserRepository();
    }

    /**
     * Asserts that the repository is seeded with the expected number of users on construction.
     */
    public function testRepositoryIsSeededOnConstruction(): void
    {
        $result = $this->repository->findPaginated(1, 100);

        $this->assertSame(5, $result['total']);
    }

    /**
     * Asserts that findPaginated returns the correct slice of users for a given page.
     */
    public function testFindPaginatedReturnsCorrectSliceForPage(): void
    {
        $result = $this->repository->findPaginated(1, 2);

        $this->assertCount(2, $result['users']);
        $this->assertSame(5, $result['total']);
    }

    /**
     * Asserts that findPaginated returns the correct users on the second page.
     */
    public function testFindPaginatedReturnsCorrectUsersOnSecondPage(): void
    {
        $firstPage = $this->repository->findPaginated(1, 2);
        $secondPage = $this->repository->findPaginated(2, 2);

        $firstPageIds = array_column(array_map(fn ($u) => ['id' => $u->getId()], $firstPage['users']), 'id');
        $secondPageIds = array_column(array_map(fn ($u) => ['id' => $u->getId()], $secondPage['users']), 'id');

        // Pages should not overlap
        $this->assertEmpty(array_intersect($firstPageIds, $secondPageIds));
    }

    /**
     * Asserts that findPaginated returns an empty users array when the page is out of range.
     */
    public function testFindPaginatedReturnsEmptyUsersForOutOfRangePage(): void
    {
        $result = $this->repository->findPaginated(10, 10);

        $this->assertEmpty($result['users']);
        $this->assertSame(5, $result['total']);
    }

    /**
     * Asserts that the total reflects all users regardless of the requested page.
     */
    public function testFindPaginatedTotalIsAlwaysTheFullCount(): void
    {
        $firstPage = $this->repository->findPaginated(1, 2);
        $secondPage = $this->repository->findPaginated(2, 2);

        $this->assertSame($firstPage['total'], $secondPage['total']);
    }
}
