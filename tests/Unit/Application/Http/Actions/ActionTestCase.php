<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Http\Actions;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\ServerRequestFactory;

/**
 * Base test case for HTTP action classes.
 *
 * This abstract class provides common utilities and helper methods for testing
 * HTTP actions in the application. It includes methods for creating PSR-7
 * requests and responses, and for asserting JSON response content.
 */
abstract class ActionTestCase extends TestCase
{
    /**
     * Assert that a response contains valid JSON and optionally check status code.
     *
     * This method validates that the response body contains valid JSON and
     * optionally verifies the HTTP status code. It returns the decoded JSON
     * data for further assertions.
     *
     * @param Response $response       The HTTP response to validate
     * @param int|null $expectedStatus Optional expected HTTP status code
     *
     * @return array<string, mixed> The decoded JSON response data
     *
     * @throws \JsonException If the response body is not valid JSON
     */
    protected function assertJsonResponse(Response $response, ?int $expectedStatus = null): array
    {
        if (null !== $expectedStatus) {
            $this->assertEquals($expectedStatus, $response->getStatusCode(), 'Unexpected HTTP status code.');
        }

        $body = (string) $response->getBody();
        $this->assertJson($body, 'Response body is not valid JSON.');

        return json_decode($body, true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Create a PSR-7 request with a JSON or form body.
     *
     * This helper method creates a server request instance with optional
     * request body data. The data is set as the parsed body, which is how
     * Slim typically provides request data to actions.
     *
     * @param array<string, mixed> $data   Request body data to include
     * @param string               $method HTTP method for the request
     * @param string               $uri    URI path for the request
     *
     * @return \Psr\Http\Message\ServerRequestInterface The created request object
     */
    protected function createRequestWithBody(array $data = [], string $method = 'POST', string $uri = '/'): \Psr\Http\Message\ServerRequestInterface
    {
        $requestFactory = new ServerRequestFactory();
        $request = $requestFactory->createServerRequest($method, $uri);

        if (!empty($data)) {
            $request = $request->withParsedBody($data);
        }

        return $request;
    }

    /**
     * Create a PSR-7 response instance.
     *
     * This helper method creates a fresh response object that can be used
     * for testing action methods. The response starts with a 200 status
     * code and empty body.
     *
     * @return Response A new PSR-7 response instance
     */
    protected function createResponse(): Response
    {
        $responseFactory = new ResponseFactory();

        return $responseFactory->createResponse();
    }
}
