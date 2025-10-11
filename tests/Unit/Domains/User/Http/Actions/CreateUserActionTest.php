<?php

declare(strict_types=1);

namespace Tests\Unit\Domains\User\Http\Actions;

use App\Domains\User\Http\Actions\CreateUserAction;
use App\Domains\User\Models\User;
use App\Domains\User\Services\UserService;
use App\Http\Responders\JsonResponder;
use Tests\Support\ActionTestCase;

final class CreateUserActionTest extends ActionTestCase
{
    public function testItCreatesUserAndReturnsJsonResponse()
    {
        $mockData = ['first_name' => 'John', 'last_name' => 'Doe'];
        $request = $this->createRequestWithBody($mockData);
        $response = $this->createResponse();

        $mockedUser = $this->createMock(User::class);
        $mockUserData = [
            'id' => 1,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'created_at' => '2025-10-10 12:00:00',
            'updated_at' => '2025-10-10 12:00:00',
        ];
        $mockedUser->method('toArray')->willReturn($mockUserData);
        $mockedUser->method('jsonSerialize')->willReturn($mockUserData);

        $mockedUserService = $this->createMock(UserService::class);
        $mockedUserService->expects($this->once())
            ->method('create')
            ->with($mockData)
            ->willReturn($mockedUser);

        $responder = new JsonResponder();
        $action = new CreateUserAction($responder, $mockedUserService);

        $result = $action($request, $response);

        $responseData = $this->assertJsonResponse($result, 201);

        $this->assertArrayHasKey('id', $responseData);
        $this->assertEquals('John', $responseData['first_name']);
        $this->assertEquals('Doe', $responseData['last_name']);
        $this->assertArrayHasKey('created_at', $responseData);
        $this->assertArrayHasKey('updated_at', $responseData);
    }

    public function testItHandlesEmptyRequestBody()
    {
        $mockData = [];
        $request = $this->createRequestWithBody($mockData);
        $response = $this->createResponse();

        $mockedUser = $this->createMock(User::class);
        $mockUserData = [
            'id' => 1,
            'first_name' => null,
            'last_name' => null,
            'created_at' => '2025-10-10 12:00:00',
            'updated_at' => '2025-10-10 12:00:00',
        ];
        $mockedUser->method('toArray')->willReturn($mockUserData);
        $mockedUser->method('jsonSerialize')->willReturn($mockUserData);

        $mockedUserService = $this->createMock(UserService::class);
        $mockedUserService->expects($this->once())
            ->method('create')
            ->with($mockData)
            ->willReturn($mockedUser);

        $responder = new JsonResponder();
        $action = new CreateUserAction($responder, $mockedUserService);

        $result = $action($request, $response);

        $responseData = $this->assertJsonResponse($result, 201);
        $this->assertArrayHasKey('id', $responseData);
    }
}
