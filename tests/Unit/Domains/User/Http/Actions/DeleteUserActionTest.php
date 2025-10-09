<?php

namespace Tests\Unit\Domains\User\Http\Actions;

use App\Domains\User\Http\Actions\DeleteUserAction;
use App\Domains\User\Repositories\UserRepository;
use App\Infrastructure\Http\Responders\JsonResponder;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class DeleteUserActionTest extends TestCase
{
    public function testItDeletesUserAndReturnsJsonResponse()
    {
        $request = $this->createMock(ServerRequestInterface::class);

        $response = $this->createMock(ResponseInterface::class);

        $userId = 1;
        $repository = $this->createMock(UserRepository::class);
        $repository->expects($this->once())
            ->method('deleteUser')
            ->with($userId);

        $expectedResponse = null;
        $responder = $this->createMock(JsonResponder::class);
        $responder->expects($this->once())
            ->method('respond')
            ->with($response, $expectedResponse, 204)
            ->willReturn($response);

        $action = new DeleteUserAction($responder, $repository);

        $result = $action($request, $response, ['id' => $userId]);

        $this->assertSame($response, $result);
    }
}
