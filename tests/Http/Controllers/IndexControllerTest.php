<?php

namespace Tests\Http\Controllers;

use App\Http\Controllers\IndexController;
use Slim\Views\Twig;

class IndexControllerTest extends AbstractControllerTestCase
{
    public function testInvoke()
    {
        // Set up the Twig environment
        $twig = Twig::create(root_path('resources/views'), [
            'cache' => false,
        ]);

        // Create a mock request and response
        $request = $this->createRequest('GET', '/');
        $response = $this->createResponse();

        // Instantiate the controller
        $controller = new IndexController($twig);

        // Invoke the controller
        $response = $controller($request, $response);

        // Assert the response content
        $this->assertStringContainsString('Hello IndexController!', (string)$response->getBody());
    }
}
