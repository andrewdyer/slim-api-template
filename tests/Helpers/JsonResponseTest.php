<?php

namespace Tests\Helpers;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Factory\ResponseFactory;

class JsonResponseTest extends TestCase
{
    public function testJsonResponseOutputsCorrectJson(): void
    {
        $responseFactory = new ResponseFactory();
        $response = $responseFactory->createResponse();

        $data = ['message' => 'Hello World'];
        $status = 201;

        $jsonResponse = json_response($response, $data, $status);

        // Assert instance
        $this->assertInstanceOf(ResponseInterface::class, $jsonResponse);

        // Assert status code
        $this->assertEquals($status, $jsonResponse->getStatusCode());

        // Assert content type
        $this->assertEquals('application/json', $jsonResponse->getHeaderLine('Content-Type'));

        // Assert body content
        $body = (string)$jsonResponse->getBody();
        $this->assertJson($body);
        $this->assertEquals(json_encode($data), $body);
    }
}
