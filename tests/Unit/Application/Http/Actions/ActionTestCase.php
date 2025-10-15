<?php

namespace Tests\Unit\Application\Http\Actions;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\ServerRequestFactory;

abstract class ActionTestCase extends TestCase
{
    /**
     * Assert that a response contains valid JSON and optionally check status code.
     *
     * @return array
     * @throws \JsonException
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
     * @param array<string, mixed> $data
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
     */
    protected function createResponse(): Response
    {
        $responseFactory = new ResponseFactory();

        return $responseFactory->createResponse();
    }
}
