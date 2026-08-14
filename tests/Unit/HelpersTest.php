<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for Helpers.
 */
#[CoversFunction('get_env')]
#[CoversFunction('get_env_array')]
final class HelpersTest extends TestCase
{
    /**
     * Backup of the original environment variables array.
     */
    private array $originalEnv;

    /**
     * Backup of the original process environment values used by these tests.
     *
     * @var array<string, string|false>
     */
    private array $originalProcessEnvironment;

    /**
     * Backup of the original server variables array.
     */
    private array $originalServer;

    /**
     * Provides boolean strings with representative casing.
     *
     * @return array<string, array{string, bool}>
     */
    public static function booleanValueProvider(): array
    {
        return [
            'true lowercase' => ['true', true],
            'false lowercase' => ['false', false],
            'true uppercase' => ['TRUE', true],
            'false uppercase' => ['FALSE', false],
            'true mixed case' => ['True', true],
            'false mixed case' => ['False', false],
        ];
    }

    /**
     * Builds backups of environment variables before each test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->originalEnv = $_ENV;
        $this->originalServer = $_SERVER;
        $this->originalProcessEnvironment = [];

        foreach (self::ENVIRONMENT_KEYS as $key) {
            $this->originalProcessEnvironment[$key] = getenv($key);

            unset($_ENV[$key], $_SERVER[$key]);
            putenv($key);
        }
    }

    /**
     * Processes restoration of the original environment variables after each test.
     */
    protected function tearDown(): void
    {
        $_ENV = $this->originalEnv;
        $_SERVER = $this->originalServer;

        foreach ($this->originalProcessEnvironment as $key => $value) {
            if ($value === false) {
                putenv($key);

                continue;
            }

            putenv("{$key}={$value}");
        }

        parent::tearDown();
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

    /**
     * Asserts that values can be split using a custom delimiter.
     */
    public function testGetEnvArrayUsesCustomDelimiter(): void
    {
        $_ENV['LIST_KEY'] = 'a|b|c';

        $this->assertSame(['a', 'b', 'c'], get_env_array('LIST_KEY', '|'));
    }

    /**
     * Asserts that boolean strings are cast without regard to case.
     *
     * @param string $value    The environment variable value to cast.
     * @param bool   $expected The expected cast value.
     */
    #[DataProvider('booleanValueProvider')]
    public function testGetEnvCastsBooleanStrings(string $value, bool $expected): void
    {
        $_ENV['FLAG_KEY'] = $value;

        $this->assertSame($expected, get_env('FLAG_KEY'));
    }

    /**
     * Asserts that $_ENV takes precedence over $_SERVER and the process environment.
     */
    public function testGetEnvPrefersEnvValue(): void
    {
        $_ENV['PRECEDENCE_KEY'] = 'env-value';
        $_SERVER['PRECEDENCE_KEY'] = 'server-value';
        putenv('PRECEDENCE_KEY=process-value');

        $this->assertSame('env-value', get_env('PRECEDENCE_KEY'));
    }

    /**
     * Asserts that $_SERVER takes precedence over the process environment.
     */
    public function testGetEnvPrefersServerValueToProcessValue(): void
    {
        $_SERVER['PRECEDENCE_KEY'] = 'server-value';
        putenv('PRECEDENCE_KEY=process-value');

        $this->assertSame('server-value', get_env('PRECEDENCE_KEY'));
    }

    /**
     * Asserts that an explicitly configured empty string is not replaced by the default.
     */
    public function testGetEnvPreservesEmptyString(): void
    {
        $_ENV['SOME_KEY'] = '';

        $this->assertSame('', get_env('SOME_KEY', 'default'));
    }

    /**
     * Asserts that the default value is returned when the key is missing.
     */
    public function testGetEnvReturnsDefaultWhenMissing(): void
    {
        $this->assertSame('default', get_env('MISSING_KEY', 'default'));
    }

    /**
     * Asserts that process environment variables are available when $_ENV is not populated.
     */
    public function testGetEnvReturnsProcessEnvironmentValue(): void
    {
        putenv('PROCESS_ENV_KEY=process-value');

        $this->assertSame('process-value', get_env('PROCESS_ENV_KEY'));
    }

    /**
     * Asserts that server environment variables are available when $_ENV is not populated.
     */
    public function testGetEnvReturnsServerEnvironmentValue(): void
    {
        $_SERVER['SERVER_ENV_KEY'] = 'server-value';

        $this->assertSame('server-value', get_env('SERVER_ENV_KEY'));
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
     * Environment variable keys used by these tests.
     *
     * @var list<string>
     */
    private const ENVIRONMENT_KEYS = [
        'SOME_KEY',
        'FLAG_KEY',
        'LIST_KEY',
        'MISSING_KEY',
        'PROCESS_ENV_KEY',
        'SERVER_ENV_KEY',
        'PRECEDENCE_KEY',
    ];
}
