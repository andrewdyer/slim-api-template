<?php

declare(strict_types=1);

namespace Tests\Integration;

/**
 * Integration tests for CORS preflight handling.
 */
final class CorsPreflightTest extends AbstractTestCase
{
    /**
     * Asserts that an OPTIONS preflight request to any route returns a 200 response.
     */
    public function testReturns200ForPreflightRequest(): void
    {
        $response = $this->request('OPTIONS', '/api/v1/users/1');

        $this->assertSame(200, $response->getStatusCode());
    }
}
