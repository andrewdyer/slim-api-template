<?php

declare(strict_types=1);

namespace Tests\Unit\Domains\User\Http\Actions;

use App\Domains\User\Exceptions\UserNotFoundException;
use App\Domains\User\Http\Actions\ShowUserAction;
use App\Domains\User\Models\User;
use App\Domains\User\Services\UserService;
use App\Http\Responders\JsonResponder;
use Tests\Support\ActionTestCase;

final class ShowUserActionTest extends ActionTestCase
{
    public function testItReturnsUserByIdAsJsonResponse()
    {
        $userId = 10;

        $request = $this->createEmptyRequest();
        $response = $this->createResponse();

        $mockedUser = $this->createMock(User::class);
        $mockUserData = [
            'id' => $userId,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'created_at' => '2025-10-10 12:00:00',
            'updated_at' => '2025-10-10 12:00:00',
        ];
        $mockedUser->method('toArray')->willReturn($mockUserData);
        $mockedUser->method('jsonSerialize')->willReturn($mockUserData);

        $mockedUserService = $this->createMock(UserService::class);
        $mockedUserService->expects($this->once())
            ->method('getById')
            ->with($userId)
            ->willReturn($mockedUser);

        $responder = new JsonResponder();
        $action = new ShowUserAction($responder, $mockedUserService);

        $result = $action($request, $response, ['id' => (string)$userId]);

        $responseData = $this->assertJsonResponse($result, 200);

        $this->assertEquals($userId, $responseData['id']);
        $this->assertEquals('John', $responseData['first_name']);
        $this->assertEquals('Doe', $responseData['last_name']);
        $this->assertArrayHasKey('created_at', $responseData);
        $this->assertArrayHasKey('updated_at', $responseData);
    }

    public function testItThrowsExceptionWhenUserNotFound()
    {
        $userId = 999;

        $request = $this->createEmptyRequest();
        $response = $this->createResponse();

        $mockedUserService = $this->createMock(UserService::class);
        $mockedUserService->expects($this->once())
            ->method('getById')
            ->with($userId)
            ->willThrowException(new UserNotFoundException($userId));

        $responder = new JsonResponder();
        $action = new ShowUserAction($responder, $mockedUserService);

        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage('User with id 999 not found');

        $action($request, $response, ['id' => (string)$userId]);
    }
}
