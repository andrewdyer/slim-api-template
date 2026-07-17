<?php

declare(strict_types=1);

namespace Tests\Integration;

use Faker\Factory as FakerFactory;
use Faker\Generator;
use JsonException;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Slim\App;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Stream;
use Slim\Psr7\Uri;

/**
 * Integration tests for AbstractTestCase.
 */
abstract class AbstractTestCase extends TestCase
{
    /**
     * The application instance under test.
     */
    protected App $app;

    /**
     * The Faker generator instance.
     */
    protected Generator $faker;

    /**
     * Sets up the application before each test.
     */
    protected function setUp(): void
    {
        $this->app = require_from_root('bootstrap/app.php')();
        $this->faker = FakerFactory::create();
    }

    /**
     * Processes an HTTP request through the application.
     *
     * @param  string                    $method The HTTP method.
     * @param  string                    $path   The request URI path (may include query string).
     * @param  array<string, mixed>|null $data   Optional JSON request body.
     * @return ResponseInterface         The application response.
     * @throws JsonException             If JSON encoding fails.
     */
    protected function request(string $method, string $path, ?array $data = null): ResponseInterface
    {
        $urlParts = is_array($parsed = parse_url($path)) ? $parsed : [];
        $uriPath = $urlParts['path'] ?? $path;
        $queryString = $urlParts['query'] ?? '';

        $uri = new Uri(
            scheme: '',
            host: '',
            port: 80,
            path: $uriPath,
            query: $queryString
        );

        $serverRequest = (new ServerRequestFactory())
            ->createServerRequest($method, $uri)
            ->withHeader('Accept', 'application/json');

        if ($data !== null) {
            $stream = fopen('php://temp', 'wb+');
            fwrite($stream, json_encode($data, JSON_THROW_ON_ERROR));
            rewind($stream);

            $serverRequest = $serverRequest
                ->withHeader('Content-Type', 'application/json')
                ->withBody(new Stream($stream))
                ->withParsedBody($data);
        }

        return $this->app->handle($serverRequest);
    }

    /**
     * Returns the decoded JSON response body.
     *
     * @param  ResponseInterface    $response The HTTP response.
     * @return array<string, mixed> The decoded response data.
     * @throws JsonException        If JSON decoding fails.
     */
    protected function decodeJson(ResponseInterface $response): array
    {
        $body = (string)$response->getBody();

        if ($body === '') {
            return [];
        }

        return json_decode($body, true, 512, JSON_THROW_ON_ERROR);
    }
}
