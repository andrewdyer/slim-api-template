<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Unit tests for helper functions.
 */
final class HelpersTest extends TestCase
{
    /**
     * Backup of the original environment variables array.
     */
    private array $originalEnv;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->originalEnv = $_ENV;

        unset($_ENV['SOME_KEY'], $_ENV['FLAG_KEY'], $_ENV['LIST_KEY'], $_ENV['MISSING_KEY']);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        $_ENV = $this->originalEnv;

        parent::tearDown();
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
     * Asserts that "TRUE" (uppercase) is cast to a boolean true.
     */
    public function testGetEnvCastsTrueUppercase(): void
    {
        $_ENV['FLAG_KEY'] = 'TRUE';

        $this->assertTrue(get_env('FLAG_KEY'));
    }

    /**
     * Asserts that "FALSE" (uppercase) is cast to a boolean false.
     */
    public function testGetEnvCastsFalseUppercase(): void
    {
        $_ENV['FLAG_KEY'] = 'FALSE';

        $this->assertFalse(get_env('FLAG_KEY'));
    }

    /**
     * Asserts that "True" (mixed-case) is cast to a boolean true.
     */
    public function testGetEnvCastsTrueMixedCase(): void
    {
        $_ENV['FLAG_KEY'] = 'True';

        $this->assertTrue(get_env('FLAG_KEY'));
    }

    /**
     * Asserts that "False" (mixed-case) is cast to a boolean false.
     */
    public function testGetEnvCastsFalseMixedCase(): void
    {
        $_ENV['FLAG_KEY'] = 'False';

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
