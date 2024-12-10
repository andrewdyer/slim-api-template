<?php

namespace Tests\Http\Controllers;

use App\Http\Controllers\IndexController;
use PHPUnit\Framework\TestCase;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\ServerRequestFactory;

class IndexControllerTest extends TestCase
{
    public function testInvoke()
    {
        // Create a mock request and response
        $requestFactory = new ServerRequestFactory();
        $responseFactory = new ResponseFactory();
        $request = $requestFactory->createServerRequest('GET', '/');
        $response = $responseFactory->createResponse();

        // Instantiate the controller
        $controller = new IndexController();

        // Invoke the controller
        $response = $controller($request, $response);

        // Assert the response content
        $this->assertEquals('Hello, World!', (string)$response->getBody());
    }
}
