<?php

namespace Tests\Unit\Application\Http\Actions\User;

use App\Application\Http\Actions\User\CreateUserAction;

final class CreateUserActionTest extends UserActionTestCase
{
    /**
     * Test that CreateUserAction successfully creates a user and returns 201.
     */
    public function testCreateUserAction(): void
    {
        $data = [
            'first_name' => 'Bob',
            'last_name' => 'Smith',
            'email' => 'bob@example.com',
        ];

        $request = $this->createRequestWithBody($data);
        $response = $this->createResponse();

        $action = new CreateUserAction($this->jsonResponder, $this->userService);
        $result = $action($request, $response, []);

        $responseData = $this->assertJsonResponse($result, 201);
        $this->assertEquals('Bob', $responseData['firstName']);
        $this->assertEquals('Smith', $responseData['lastName']);
        $this->assertEquals('bob@example.com', $responseData['email']);
    }
}
