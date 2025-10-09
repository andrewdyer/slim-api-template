<?php

namespace Tests\Unit\Domains\User\Http\Actions;

use App\Domains\User\Http\Actions\ListUsersAction;
use App\Domains\User\Repositories\UserRepository;
use App\Infrastructure\Http\Responders\JsonResponder;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ListUsersActionTest extends TestCase
{
    public function testItReturnsListOfUsersAsJsonResponse()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $expectedUsers = [
            ['id' => 1, 'title' => 'Bug report'],
            ['id' => 2, 'title' => 'Feature request'],
        ];

        $repository = $this->createMock(UserRepository::class);
        $repository->expects($this->once())
            ->method('getUsers')
            ->willReturn($expectedUsers);

        $responder = $this->createMock(JsonResponder::class);
        $responder->expects($this->once())
            ->method('respond')
            ->with($response, $expectedUsers)
            ->willReturn($response);

        $action = new ListUsersAction($responder, $repository);

        $result = $action($request, $response);

        $this->assertSame($response, $result);
    }
}
