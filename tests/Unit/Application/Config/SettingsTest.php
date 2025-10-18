<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Config;

use App\Application\Config\Settings;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for the Settings application configuration holder.
 *
 * This test class verifies that the Settings object correctly exposes
 * configuration data, both as a whole and by individual keys, including
 * nested arrays.
 */
final class SettingsTest extends TestCase
{
    /**
     * Test that get() returns the value for an existing key.
     *
     * Verifies that when a valid key is provided, the expected value is returned.
     *
     * @return void
     */
    public function testGetExistingKeyReturnsValue(): void
    {
        $settings = new Settings(['foo' => 'bar']);

        $this->assertSame('bar', $settings->get('foo'));
    }

    /**
     * Test that get() returns the default value if key is missing.
     *
     * Confirms that when a key does not exist, the provided default is returned instead of throwing.
     *
     * @return void
     */
    public function testGetMissingKeyReturnsDefault(): void
    {
        $settings = new Settings(['foo' => 'bar']);

        $this->assertSame('default', $settings->get('baz', 'default'));
        $this->assertNull($settings->get('baz', null));
    }

    /**
     * Test that get() throws an exception if key is missing and no default provided.
     *
     * Ensures that missing keys without a default trigger an InvalidArgumentException.
     *
     * @return void
     */
    public function testGetMissingKeyThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Configuration key 'baz' not found.");

        $settings = new Settings(['foo' => 'bar']);
        $settings->get('baz');
    }

    /**
     * Test that get() returns nested arrays correctly.
     *
     * Ensures that nested configuration values are returned intact when requested by their top-level key.
     *
     * @return void
     */
    public function testGetNestedKeyReturnsArray(): void
    {
        $settings = new Settings(['nested' => ['a' => 1]]);

        $this->assertSame(['a' => 1], $settings->get('nested'));
    }

    /**
     * Test that has($key) accurately reports the existence of keys.
     *
     * Verifies that the Settings instance correctly identifies present and
     * missing keys, treating keys set to null as existing because the check
     * uses array_key_exists().
     *
     * @return void
     */
    public function testHasDetectsExistingAndMissingKeys(): void
    {
        $data = ['present' => null, 'other' => 123];

        $settings = new Settings($data);

        $this->assertTrue($settings->has('present'));
        $this->assertTrue($settings->has('other'));
        $this->assertFalse($settings->has('missing'));
    }
}
