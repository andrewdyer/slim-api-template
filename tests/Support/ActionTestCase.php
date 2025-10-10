<?php

declare(strict_types=1);

namespace Tests\Support;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Response;

abstract class ActionTestCase extends TestCase
{
    protected function createRequestWithBody(array $data): ServerRequestInterface
    {
        $serverRequestFactory = new ServerRequestFactory();
        $streamFactory = new StreamFactory();

        $request = $serverRequestFactory->createServerRequest('POST', '/');
        $stream = $streamFactory->createStream(json_encode($data));

        return $request->withBody($stream)->withParsedBody($data);
    }

    protected function createEmptyRequest(): ServerRequestInterface
    {
        $serverRequestFactory = new ServerRequestFactory();

        return $serverRequestFactory->createServerRequest('GET', '/');
    }

    protected function createResponse(): ResponseInterface
    {
        return new Response();
    }

    protected function assertJsonResponse($response, int $expectedStatus = 200): array
    {
        $this->assertEquals($expectedStatus, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));

        $body = (string)$response->getBody();
        $data = json_decode($body, true);

        $this->assertNotNull($data, 'Response body should be valid JSON');

        return $data;
    }
}
