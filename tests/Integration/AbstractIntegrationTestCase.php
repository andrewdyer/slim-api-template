<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Infrastructure\Application;
use App\Infrastructure\Factory\ApplicationFactory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Stream;
use Slim\Psr7\Uri;

/**
 * Base class for integration tests using the full application stack.
 */
abstract class AbstractIntegrationTestCase extends TestCase
{
    /**
     * The application instance under test.
     */
    protected Application $application;

    /**
     * Sets up the application before each test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $request = (new ServerRequestFactory())
            ->createServerRequest('GET', '/');

        $this->application = ApplicationFactory::create($request);
    }

    /**
     * Processes an HTTP request through the application.
     *
     * @param  string                    $method The HTTP method.
     * @param  string                    $path   The request URI path.
     * @param  array<string, mixed>|null $data   Optional JSON request body.
     * @return ResponseInterface         Returns the application response.
     */
    protected function request(string $method, string $path, ?array $data = null): ResponseInterface
    {
        $uri = new Uri('', '', 80, $path);

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

        return $this->application->handle($serverRequest);
    }

    /**
     * Returns the decoded JSON response body.
     *
     * @param  ResponseInterface    $response The HTTP response.
     * @return array<string, mixed> Returns the decoded response data.
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
