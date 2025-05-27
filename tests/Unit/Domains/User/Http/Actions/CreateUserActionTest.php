<?php

namespace Tests\Unit\Domains\User\Http\Actions;

use App\Domains\User\Http\Actions\CreateUserAction;
use App\Domains\User\Repositories\UserRepository;
use App\Http\Responders\JsonResponder;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class CreateUserActionTest extends TestCase
{
    public function testItCreatesUserAndReturnsJsonResponse()
    {
        $data = [];
        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getParsedBody')
            ->willReturn($data);

        $response = $this->createMock(ResponseInterface::class);

        $expectedResponse = [];
        $repository = $this->createMock(UserRepository::class);
        $repository->expects($this->once())
            ->method('createUser')
            ->with($data)
            ->willReturn($expectedResponse);

        $responder = $this->createMock(JsonResponder::class);
        $responder->expects($this->once())
            ->method('respond')
            ->with($response, $expectedResponse, 201)
            ->willReturn($response);

        $action = new CreateUserAction($responder, $repository);

        $result = $action($request, $response);

        $this->assertSame($response, $result);
    }
}
