<?php

declare(strict_types=1);

namespace Tests\Http\Controllers\Api;

use App\Http\Controllers\Api\UserController;
use Tests\Http\Controllers\AbstractControllerTestCase;

final class UserControllerTest extends AbstractControllerTestCase
{
    public function testIndexReturnsResourceList(): void
    {
        $request = $this->createRequest('GET', '/users');
        $response = $this->createResponse();

        $controller = new UserController();
        $response = $controller->index($request, $response);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));

        $body = (string)$response->getBody();
        $data = json_decode($body, true);
        $this->assertIsArray($data);
    }

    public function testShowReturnsSingleResource(): void
    {
        $request = $this->createRequest('GET', '/users/1');
        $response = $this->createResponse();
        $args = ['id' => 1];

        $controller = new UserController();
        $response = $controller->show($request, $response, $args);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));

        $data = json_decode((string)$response->getBody(), true);
        $this->assertArrayHasKey('id', $data);
        $this->assertEquals(1, $data['id']);
    }

    public function testStoreCreatesNewResource(): void
    {
        $postData = ['name' => 'John Doe'];
        $request = $this->createRequest('POST', '/users')->withParsedBody($postData);
        $response = $this->createResponse();

        $controller = new UserController();
        $response = $controller->store($request, $response);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));

        $body = (string)$response->getBody();
        $data = json_decode($body, true);
        $this->assertArrayHasKey('message', $data);
        $this->assertStringContainsString('created', strtolower($data['message']));
    }

    public function testUpdateModifiesExistingResource(): void
    {
        $putData = ['name' => 'John Doe'];
        $request = $this->createRequest('PUT', '/users/1')->withParsedBody($putData);
        $response = $this->createResponse();
        $args = ['id' => 1];

        $controller = new UserController();
        $response = $controller->update($request, $response, $args);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));

        $data = json_decode((string)$response->getBody(), true);
        $this->assertArrayHasKey('message', $data);
        $this->assertStringContainsString('updated', strtolower($data['message']));
    }

    public function testDestroyDeletesResource(): void
    {
        $request = $this->createRequest('DELETE', '/users/1');
        $response = $this->createResponse();
        $args = ['id' => 1];

        $controller = new UserController();
        $response = $controller->destroy($request, $response, $args);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));

        $data = json_decode((string)$response->getBody(), true);
        $this->assertArrayHasKey('message', $data);
        $this->assertStringContainsString('deleted', strtolower($data['message']));
    }
}
