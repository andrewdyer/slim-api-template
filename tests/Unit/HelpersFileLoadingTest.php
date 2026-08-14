<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Unit tests for HelpersFileLoadingTest.
 *
 * Deliberately carries no #[CoversFunction] metadata. HelpersGetEnvTest and
 * HelpersRequireFromRootTest each restrict coverage to their own functions'
 * bodies, which excludes the top-level function_exists() guards wrapping
 * each declaration -- those only run once per process and are otherwise
 * invisible to any restricted-coverage test.
 */
final class HelpersFileLoadingTest extends TestCase
{
    /**
     * Asserts that requiring helpers.php a second time does not redeclare its functions.
     */
    public function testHelpersFileIsSafeToRequireMoreThanOnce(): void
    {
        require root_path('app/helpers.php');

        $this->assertTrue(function_exists('root_path'));
        $this->assertTrue(function_exists('get_env'));
        $this->assertTrue(function_exists('get_env_array'));
        $this->assertTrue(function_exists('require_from_root'));
    }
}
