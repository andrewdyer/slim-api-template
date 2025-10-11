<?php

declare(strict_types=1);

namespace Tests\Unit\Domains\User\Http\Actions;

use App\Domains\User\Http\Actions\ListUsersAction;
use App\Domains\User\Services\UserService;
use App\Http\Responders\JsonResponder;
use Tests\Support\ActionTestCase;

final class ListUsersActionTest extends ActionTestCase
{
    public function testItReturnsListOfUsersAsJsonResponse()
    {
        $request = $this->createEmptyRequest();
        $response = $this->createResponse();

        $mockUsersData = [
            ['id' => 1, 'first_name' => 'John', 'last_name' => 'Doe'],
            ['id' => 2, 'first_name' => 'Jane', 'last_name' => 'Smith'],
        ];

        $mockedUserService = $this->createMock(UserService::class);
        $mockedUserService->expects($this->once())
            ->method('getAll')
            ->willReturn($mockUsersData);

        $responder = new JsonResponder();
        $action = new ListUsersAction($responder, $mockedUserService);

        $result = $action($request, $response);

        $responseData = $this->assertJsonResponse($result, 200);

        $this->assertIsArray($responseData);
        $this->assertCount(2, $responseData);

        $this->assertEquals(1, $responseData[0]['id']);
        $this->assertEquals('John', $responseData[0]['first_name']);
        $this->assertEquals('Doe', $responseData[0]['last_name']);

        $this->assertEquals(2, $responseData[1]['id']);
        $this->assertEquals('Jane', $responseData[1]['first_name']);
        $this->assertEquals('Smith', $responseData[1]['last_name']);
    }

    public function testItReturnsEmptyArrayWhenNoUsers()
    {
        $request = $this->createEmptyRequest();
        $response = $this->createResponse();

        $mockedUserService = $this->createMock(UserService::class);
        $mockedUserService->expects($this->once())
            ->method('getAll')
            ->willReturn([]);

        $responder = new JsonResponder();
        $action = new ListUsersAction($responder, $mockedUserService);

        $result = $action($request, $response);

        $responseData = $this->assertJsonResponse($result, 200);
        $this->assertIsArray($responseData);
        $this->assertEmpty($responseData);
    }
}
