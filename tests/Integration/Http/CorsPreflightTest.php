<?php

declare(strict_types=1);

namespace Tests\Integration\Http;

use Tests\Integration\AbstractTestCase;

/**
 * Integration tests for CORS preflight handling.
 */
final class CorsPreflightTest extends AbstractTestCase
{
    /**
     * Asserts that an OPTIONS preflight request to a nested route returns a 200 response.
     */
    public function testReturns200ForPreflightRequestToNestedRoute(): void
    {
        $response = $this->request('OPTIONS', '/api/v1/users/1', headers: self::PREFLIGHT_HEADERS);

        $this->assertSame(200, $response->getStatusCode());
    }

    /**
     * Asserts that an OPTIONS preflight request to the root route returns a 200 response.
     */
    public function testReturns200ForPreflightRequestToRootRoute(): void
    {
        $response = $this->request('OPTIONS', '/', headers: self::PREFLIGHT_HEADERS);

        $this->assertSame(200, $response->getStatusCode());
    }
    /**
     * The standard headers a browser sends with a CORS preflight request.
     *
     * @var array<string, string>
     */
    private const array PREFLIGHT_HEADERS = [
        'Origin' => 'http://localhost:3000',
        'Access-Control-Request-Method' => 'PUT',
        'Access-Control-Request-Headers' => 'Content-Type',
    ];
}
