<?php

namespace Tests\Unit\Domains\User\Http\Actions;

use App\Domains\User\Http\Actions\ShowUserAction;
use App\Domains\User\Repositories\UserRepository;
use App\Infrastructure\Http\Responders\JsonResponder;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ShowUserActionTest extends TestCase
{
    public function testItReturnsUserByIdAsJsonResponse()
    {
        $userId = 10;

        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $expectedUser = ['id' => $userId, 'title' => 'Show me ticket'];

        $repository = $this->createMock(UserRepository::class);
        $repository->expects($this->once())
            ->method('getUserById')
            ->with($userId)
            ->willReturn($expectedUser);

        $responder = $this->createMock(JsonResponder::class);
        $responder->expects($this->once())
            ->method('respond')
            ->with($response, $expectedUser)
            ->willReturn($response);

        $action = new ShowUserAction($responder, $repository);

        $result = $action($request, $response, ['id' => (string)$userId]);

        $this->assertSame($response, $result);
    }
}
