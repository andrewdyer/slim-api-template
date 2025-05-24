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
}
