<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * Unit tests for HelpersRequireFromRootTest.
 */
#[CoversFunction('require_from_root')]
#[CoversFunction('root_path')]
final class HelpersRequireFromRootTest extends TestCase
{
    /**
     * Asserts that the required file's return value is returned.
     */
    public function testRequireFromRootReturnsTheRequiredFilesReturnValue(): void
    {
        $this->assertSame(
            ['fixture' => true],
            require_from_root('tests/Support/Fixtures/require-from-root-fixture.php')
        );
    }

    /**
     * Asserts that a RuntimeException is thrown when the given file does not exist.
     */
    public function testRequireFromRootThrowsRuntimeExceptionWhenFileDoesNotExist(): void
    {
        $this->expectException(RuntimeException::class);

        require_from_root('tests/Support/Fixtures/does-not-exist.php');
    }

    /**
     * Asserts that a relative path is appended to the project root.
     */
    public function testRootPathAppendsGivenPathToProjectRoot(): void
    {
        $this->assertSame(
            realpath(dirname(__DIR__, 2) . '/composer.json'),
            realpath(root_path('composer.json'))
        );
    }

    /**
     * Asserts that the project root is returned when no path is given.
     */
    public function testRootPathReturnsProjectRootWhenNoPathIsGiven(): void
    {
        $this->assertSame(realpath(dirname(__DIR__, 2)), realpath(root_path()));
    }

    /**
     * Asserts that leading slashes are trimmed from the given path before it is appended.
     */
    public function testRootPathTrimsLeadingSlashesFromGivenPath(): void
    {
        $this->assertSame(root_path('composer.json'), root_path('/composer.json'));
    }
}
