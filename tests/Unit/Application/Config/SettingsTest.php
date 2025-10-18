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
     * Test that get() with no key returns the full settings array.
     *
     * Verifies that when no key is provided the Settings instance returns
     * the entire configuration array that was passed to the constructor.
     *
     * @return void
     */
    public function testGetAllReturnsSettingsArray(): void
    {
        $data = ['foo' => 'bar', 'nested' => ['a' => 1]];

        $settings = new Settings($data);

        $this->assertSame($data, $settings->get());
    }

    /**
     * Test that get($key) returns the value for an existing key.
     *
     * Verifies that when a valid key is provided the expected scalar value
     * is returned from the Settings instance.
     *
     * @return void
     */
    public function testGetExistingKeyReturnsValue(): void
    {
        $data = ['foo' => 'bar'];

        $settings = new Settings($data);

        $this->assertSame('bar', $settings->get('foo'));
    }

    /**
     * Test that get($key) returns nested arrays as expected.
     *
     * Verifies that nested configuration values (arrays) are returned intact
     * when requested by their top-level key.
     *
     * @return void
     */
    public function testGetNestedKeyReturnsArray(): void
    {
        $data = ['nested' => ['a' => 1]];

        $settings = new Settings($data);

        $this->assertSame(['a' => 1], $settings->get('nested'));
    }
}
