<?php

declare(strict_types=1);

namespace Tests\Unit\Domains\User\Http\Actions;

use App\Domains\User\Exceptions\UserNotFoundException;
use App\Domains\User\Http\Actions\DeleteUserAction;
use App\Domains\User\Services\UserService;
use App\Infrastructure\Http\Responders\JsonResponder;
use Tests\Support\ActionTestCase;

final class DeleteUserActionTest extends ActionTestCase
{
    public function testItDeletesUserAndReturnsJsonResponse()
    {
        $request = $this->createEmptyRequest();
        $response = $this->createResponse();

        $userId = 1;
        $mockedUserService = $this->createMock(UserService::class);
        $mockedUserService->expects($this->once())
            ->method('delete')
            ->with($userId)
            ->willReturn(true);

        $responder = new JsonResponder();
        $action = new DeleteUserAction($responder, $mockedUserService);

        $result = $action($request, $response, ['id' => $userId]);

        $this->assertEquals(204, $result->getStatusCode());
        $this->assertEquals('application/json', $result->getHeaderLine('Content-Type'));

        $body = (string)$result->getBody();
        $this->assertTrue(empty($body) || $body === 'null');
    }

    public function testItHandlesStringIdParameter()
    {
        $request = $this->createEmptyRequest();
        $response = $this->createResponse();

        $userId = 42;
        $mockedUserService = $this->createMock(UserService::class);
        $mockedUserService->expects($this->once())
            ->method('delete')
            ->with($userId)
            ->willReturn(true);

        $responder = new JsonResponder();
        $action = new DeleteUserAction($responder, $mockedUserService);

        $result = $action($request, $response, ['id' => (string)$userId]);

        $this->assertEquals(204, $result->getStatusCode());
    }

    public function testItThrowsExceptionWhenUserNotFound()
    {
        $request = $this->createEmptyRequest();
        $response = $this->createResponse();

        $userId = 999;
        $mockedUserService = $this->createMock(UserService::class);
        $mockedUserService->expects($this->once())
            ->method('delete')
            ->with($userId)
            ->willThrowException(new UserNotFoundException($userId));

        $responder = new JsonResponder();
        $action = new DeleteUserAction($responder, $mockedUserService);

        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage('User with id 999 not found');

        $action($request, $response, ['id' => (string)$userId]);
    }
}
