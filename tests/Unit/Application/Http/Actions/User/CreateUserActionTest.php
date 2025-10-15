<?php

namespace Tests\Unit\Application\Http\Actions\User;

use App\Application\Http\Actions\User\CreateUserAction;

/**
 * Tests for the CreateUserAction HTTP action.
 *
 * This test class verifies that the CreateUserAction properly handles
 * user creation requests, validates input data, and returns appropriate
 * responses.
 */
final class CreateUserActionTest extends UserActionTestCase
{
    /**
     * Test that CreateUserAction successfully creates a user and returns 201.
     *
     * This test verifies that a POST request with valid user data results in
     * the creation of a new user entity and returns the correct HTTP status
     * code (201 Created) along with the user data in the response.
     *
     * @return void
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
