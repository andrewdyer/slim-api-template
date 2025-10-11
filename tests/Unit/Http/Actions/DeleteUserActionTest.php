<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Actions;

use App\Domain\User\Exceptions\UserNotFoundException;
use App\Domain\User\Services\UserService;
use App\Http\Actions\DeleteUserAction;
use App\Http\Responders\JsonResponder;
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
