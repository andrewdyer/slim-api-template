<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Unit tests for helper functions.
 */
final class HelpersTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $_ENV = [];
    }

    /**
     * Asserts that the default value is returned when the key is missing.
     */
    public function testGetEnvReturnsDefaultWhenMissing(): void
    {
        $this->assertSame('default', get_env('MISSING_KEY', 'default'));
    }

    /**
     * Asserts that string values are returned without modification.
     */
    public function testGetEnvReturnsStringValue(): void
    {
        $_ENV['SOME_KEY'] = 'some-value';

        $this->assertSame('some-value', get_env('SOME_KEY'));
    }

    /**
     * Asserts that "true" is cast to a boolean true.
     */
    public function testGetEnvCastsTrue(): void
    {
        $_ENV['FLAG_KEY'] = 'true';

        $this->assertTrue(get_env('FLAG_KEY'));
    }

    /**
     * Asserts that "false" is cast to a boolean false.
     */
    public function testGetEnvCastsFalse(): void
    {
        $_ENV['FLAG_KEY'] = 'false';

        $this->assertFalse(get_env('FLAG_KEY'));
    }

    /**
     * Asserts that an empty array is returned when the key is missing.
     */
    public function testGetEnvArrayReturnsEmptyWhenMissing(): void
    {
        $this->assertSame([], get_env_array('LIST_KEY'));
    }

    /**
     * Asserts that comma-separated values are split into an array.
     */
    public function testGetEnvArraySplitsValues(): void
    {
        $_ENV['LIST_KEY'] = 'a,b,c';

        $this->assertSame(['a', 'b', 'c'], get_env_array('LIST_KEY'));
    }

    /**
     * Asserts that values are trimmed and empty entries are removed.
     */
    public function testGetEnvArrayTrimsAndFilters(): void
    {
        $_ENV['LIST_KEY'] = ' a, ,b , , c ';

        $this->assertSame(['a', 'b', 'c'], get_env_array('LIST_KEY'));
    }
}
