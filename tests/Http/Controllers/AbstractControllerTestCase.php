<?php

namespace Tests\Http\Controllers;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\ServerRequestFactory;

abstract class AbstractControllerTestCase extends TestCase
{
    private ServerRequestFactory $requestFactory;
    private ResponseFactory $responseFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->requestFactory = new ServerRequestFactory();
        $this->responseFactory = new ResponseFactory();
    }

    protected function createRequest(string $method, string $uri): Request
    {
        return $this->requestFactory->createServerRequest($method, $uri);
    }

    protected function createResponse(): Response
    {
        return $this->responseFactory->createResponse();
    }
}
