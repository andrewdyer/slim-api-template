<?php

namespace Tests\Http\Controllers;

use App\Http\Controllers\IndexController;

class IndexControllerTest extends AbstractControllerTestCase
{
    public function testInvoke()
    {
        // Create a mock request and response
        $request = $this->createRequest('GET', '/');
        $response = $this->createResponse();

        // Instantiate the controller
        $controller = new IndexController();

        // Invoke the controller
        $response = $controller($request, $response);

        // Assert the response content
        $this->assertEquals('Hello, World!', (string)$response->getBody());
    }
}
