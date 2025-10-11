<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Actions;

use App\Domain\User\Models\User;
use App\Domain\User\Services\UserService;
use App\Http\Actions\CreateUserAction;
use App\Http\Responders\JsonResponder;
use InvalidArgumentException;
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

    public function testItThrowsExceptionWhenBothFieldsAreMissing()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('First name is required');

        $mockData = [];
        $request = $this->createRequestWithBody($mockData);
        $response = $this->createResponse();

        $mockedUserService = $this->createMock(UserService::class);
        $mockedUserService->expects($this->once())
            ->method('create')
            ->with($mockData)
            ->willThrowException(new InvalidArgumentException('First name is required'));

        $responder = new JsonResponder();
        $action = new CreateUserAction($responder, $mockedUserService);

        $action($request, $response);
    }

    public function testItThrowsExceptionWhenFirstNameIsMissing()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('First name is required');

        $mockData = ['last_name' => 'Doe'];
        $request = $this->createRequestWithBody($mockData);
        $response = $this->createResponse();

        $mockedUserService = $this->createMock(UserService::class);
        $mockedUserService->expects($this->once())
            ->method('create')
            ->with($mockData)
            ->willThrowException(new InvalidArgumentException('First name is required'));

        $responder = new JsonResponder();
        $action = new CreateUserAction($responder, $mockedUserService);

        $action($request, $response);
    }

    public function testItThrowsExceptionWhenLastNameIsMissing()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Last name is required');

        $mockData = ['first_name' => 'John'];
        $request = $this->createRequestWithBody($mockData);
        $response = $this->createResponse();

        $mockedUserService = $this->createMock(UserService::class);
        $mockedUserService->expects($this->once())
            ->method('create')
            ->with($mockData)
            ->willThrowException(new InvalidArgumentException('Last name is required'));

        $responder = new JsonResponder();
        $action = new CreateUserAction($responder, $mockedUserService);

        $action($request, $response);
    }

    public function testItThrowsExceptionWhenFirstNameIsEmpty()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('First name is required');

        $mockData = ['first_name' => '', 'last_name' => 'Doe'];
        $request = $this->createRequestWithBody($mockData);
        $response = $this->createResponse();

        $mockedUserService = $this->createMock(UserService::class);
        $mockedUserService->expects($this->once())
            ->method('create')
            ->with($mockData)
            ->willThrowException(new InvalidArgumentException('First name is required'));

        $responder = new JsonResponder();
        $action = new CreateUserAction($responder, $mockedUserService);

        $action($request, $response);
    }

    public function testItThrowsExceptionWhenLastNameIsEmpty()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Last name is required');

        $mockData = ['first_name' => 'John', 'last_name' => ''];
        $request = $this->createRequestWithBody($mockData);
        $response = $this->createResponse();

        $mockedUserService = $this->createMock(UserService::class);
        $mockedUserService->expects($this->once())
            ->method('create')
            ->with($mockData)
            ->willThrowException(new InvalidArgumentException('Last name is required'));

        $responder = new JsonResponder();
        $action = new CreateUserAction($responder, $mockedUserService);

        $action($request, $response);
    }
}
