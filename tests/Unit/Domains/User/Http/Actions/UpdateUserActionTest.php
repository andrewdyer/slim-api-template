<?php

namespace Tests\Unit\Domains\User\Http\Actions;

use App\Domains\User\Http\Actions\UpdateUserAction;
use App\Domains\User\Repositories\UserRepository;
use App\Http\Responders\JsonResponder;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class UpdateUserActionTest extends TestCase
{
    public function testItUpdatesUserAndReturnsJsonResponse()
    {
        $userId = 5;
        $mockData = ['title' => 'Updated', 'description' => 'Updated desc'];

        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getParsedBody')
            ->willReturn($mockData);

        $response = $this->createMock(ResponseInterface::class);

        $updatedUser = ['id' => $userId, 'title' => 'Updated'];

        $repository = $this->createMock(UserRepository::class);
        $repository->expects($this->once())
            ->method('updateUser')
            ->with($userId, $mockData)
            ->willReturn($updatedUser);

        $responder = $this->createMock(JsonResponder::class);
        $responder->expects($this->once())
            ->method('respond')
            ->with($response, $updatedUser, 201)
            ->willReturn($response);

        $action = new UpdateUserAction($responder, $repository);

        $result = $action($request, $response, ['id' => (string)$userId]);

        $this->assertSame($response, $result);
    }
}
