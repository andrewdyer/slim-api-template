<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Actions;

use App\Domain\User\Models\User;
use App\Domain\User\Services\UserService;
use App\Http\Actions\ListUsersAction;
use App\Http\Responders\JsonResponder;
use Illuminate\Database\Eloquent\Collection;
use Tests\Support\ActionTestCase;

final class ListUsersActionTest extends ActionTestCase
{
    public function testItReturnsListOfUsersAsJsonResponse()
    {
        $request = $this->createEmptyRequest();
        $response = $this->createResponse();

        $user1 = new User();
        $user1->id = 1;
        $user1->first_name = 'John';
        $user1->last_name = 'Doe';

        $user2 = new User();
        $user2->id = 2;
        $user2->first_name = 'Jane';
        $user2->last_name = 'Smith';

        $mockUsersCollection = new Collection([$user1, $user2]);

        $mockedUserService = $this->createMock(UserService::class);
        $mockedUserService->expects($this->once())
            ->method('getAll')
            ->willReturn($mockUsersCollection);

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

        $mockEmptyCollection = new Collection([]);

        $mockedUserService = $this->createMock(UserService::class);
        $mockedUserService->expects($this->once())
            ->method('getAll')
            ->willReturn($mockEmptyCollection);

        $responder = new JsonResponder();
        $action = new ListUsersAction($responder, $mockedUserService);

        $result = $action($request, $response);

        $responseData = $this->assertJsonResponse($result, 200);
        $this->assertIsArray($responseData);
        $this->assertEmpty($responseData);
    }
}
