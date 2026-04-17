<?php

namespace Tests\Integration;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Slim\App;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Stream;
use Slim\Psr7\Uri;

/**
 * Base class providing HTTP request helpers for integration tests.
 *
 * Bootstraps a real application instance and exposes convenience methods for
 * dispatching requests and decoding JSON responses.
 */
abstract class AbstractIntegrationTestCase extends TestCase
{
    /**
     * Bootstraps and returns a new Slim application instance.
     *
     * @return App A fully configured Slim application.
     */
    protected function createApp(): App
    {
        $factory = require root_path('bootstrap/app.php');

        $request = (new ServerRequestFactory())->createServerRequest('GET', '/');

        return $factory($request);
    }

    /**
     * Dispatches an HTTP request through the application and returns the response.
     *
     * @param  string                    $method The HTTP method (e.g. GET, POST, PUT, DELETE).
     * @param  string                    $path   The request URI path.
     * @param  array<string, mixed>|null $data   Optional request body data, encoded as JSON.
     * @return ResponseInterface         The HTTP response returned by the application.
     */
    protected function request(string $method, string $path, ?array $data = null): ResponseInterface
    {
        $uri = new Uri('', '', 80, $path);
        $headers = new Headers();
        $serverRequest = (new ServerRequestFactory())
            ->createServerRequest($method, $uri)
            ->withHeader('Accept', 'application/json');

        if (null !== $data) {
            $stream = fopen('php://temp', 'wb+');
            fwrite($stream, json_encode($data, JSON_THROW_ON_ERROR));
            rewind($stream);

            $serverRequest = $serverRequest
                ->withHeader('Content-Type', 'application/json')
                ->withBody(new Stream($stream))
                ->withParsedBody($data);
        }

        return $this->createApp()->handle($serverRequest);
    }

    /**
     * Decodes the JSON body of an HTTP response into an associative array.
     *
     * @param  ResponseInterface    $response The HTTP response to decode.
     * @return array<string, mixed> The decoded response body, or an empty array if the body is empty.
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
