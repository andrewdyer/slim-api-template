<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

final class HelpersTest extends TestCase
{
    public function testGetEnv()
    {
        $_ENV['TEST_KEY'] = 'value';
        $this->assertEquals('value', get_env('TEST_KEY'));
        $this->assertEquals('default', get_env('NON_EXISTENT_KEY', 'default'));
    }

    public function testGetEnvBool()
    {
        $_ENV['TEST_BOOL_TRUE'] = 'true';
        $_ENV['TEST_BOOL_FALSE'] = 'false';
        $this->assertTrue(get_env_bool('TEST_BOOL_TRUE'));
        $this->assertFalse(get_env_bool('TEST_BOOL_FALSE'));
        $this->assertFalse(get_env_bool('NON_EXISTENT_BOOL', false));
    }

    public function testGetEnvInt()
    {
        $_ENV['TEST_INT'] = '123';
        $this->assertEquals(123, get_env_int('TEST_INT'));
        $this->assertEquals(0, get_env_int('NON_EXISTENT_INT'));
    }
}
