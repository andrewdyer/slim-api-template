<?php

declare(strict_types=1);

namespace Tests\Unit\Domains\User\Http\Actions;

use App\Domains\User\Http\Actions\UpdateUserAction;
use App\Domains\User\Models\User;
use App\Domains\User\Services\UserService;
use App\Http\Responders\JsonResponder;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Support\ActionTestCase;

final class UpdateUserActionTest extends ActionTestCase
{
    public function testItUpdatesUserAndReturnsJsonResponse()
    {
        $userId = 1;
        $mockData = ['first_name' => 'John', 'last_name' => 'Doe'];

        $request = $this->createRequestWithBody($mockData);
        $response = $this->createResponse();

        $mockedUser = $this->createMock(User::class);
        $mockUserData = [
            'id' => $userId,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'created_at' => '2025-10-10 12:00:00',
            'updated_at' => '2025-10-10 12:30:00',
        ];
        $mockedUser->method('toArray')->willReturn($mockUserData);
        $mockedUser->method('jsonSerialize')->willReturn($mockUserData);

        $mockedUserService = $this->createMock(UserService::class);
        $mockedUserService->expects($this->once())
            ->method('update')
            ->with($userId, $mockData)
            ->willReturn($mockedUser);

        $responder = new JsonResponder();
        $action = new UpdateUserAction($responder, $mockedUserService);

        $result = $action($request, $response, ['id' => (string)$userId]);

        $responseData = $this->assertJsonResponse($result, 201);

        $this->assertEquals($userId, $responseData['id']);
        $this->assertEquals('John', $responseData['first_name']);
        $this->assertEquals('Doe', $responseData['last_name']);
        $this->assertArrayHasKey('created_at', $responseData);
        $this->assertArrayHasKey('updated_at', $responseData);
    }

    public function testItUpdatesUserWithPartialData()
    {
        $userId = 1;
        $mockPartialData = ['first_name' => 'Jane'];

        $request = $this->createRequestWithBody($mockPartialData);
        $response = $this->createResponse();

        $mockedUser = $this->createMock(User::class);
        $mockUserData = [
            'id' => $userId,
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'created_at' => '2025-10-10 12:00:00',
            'updated_at' => '2025-10-10 12:35:00',
        ];
        $mockedUser->method('toArray')->willReturn($mockUserData);
        $mockedUser->method('jsonSerialize')->willReturn($mockUserData);

        $mockedUserService = $this->createMock(UserService::class);
        $mockedUserService->expects($this->once())
            ->method('update')
            ->with($userId, $mockPartialData)
            ->willReturn($mockedUser);

        $responder = new JsonResponder();
        $action = new UpdateUserAction($responder, $mockedUserService);

        $result = $action($request, $response, ['id' => (string)$userId]);

        $responseData = $this->assertJsonResponse($result, 201);
        $this->assertEquals('Jane', $responseData['first_name']);
        $this->assertEquals('Doe', $responseData['last_name']);
    }

    public static function invalidNameDataProvider(): array
    {
        return [
            ['first_name', ''],
            ['first_name', '   '],
            ['first_name', 123],
            ['first_name', false],
            ['last_name', ''],
            ['last_name', '   '],
            ['last_name', 123],
            ['last_name', false],
        ];
    }

    #[DataProvider('invalidNameDataProvider')]
    public function testItThrowsExceptionForInvalidNames(string $field, mixed $value): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $userId = 1;
        $mockData = [$field => $value];

        $request = $this->createRequestWithBody($mockData);
        $response = $this->createResponse();

        $mockedUserService = $this->createMock(UserService::class);
        $mockedUserService->expects($this->once())
            ->method('update')
            ->with($userId, $mockData)
            ->willThrowException(new \InvalidArgumentException());

        $responder = new JsonResponder();
        $action = new UpdateUserAction($responder, $mockedUserService);

        $action($request, $response, ['id' => (string)$userId]);
    }
}
