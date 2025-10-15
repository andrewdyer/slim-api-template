<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Http\Actions\User;

use App\Application\Http\Actions\User\UpdateUserAction;

/**
 * Test cases for the UpdateUserAction HTTP action.
 *
 * This test class verifies that the update user action correctly processes
 * user update requests and returns the updated user data in JSON format.
 */
final class UpdateUserActionTest extends UserActionTestCase
{
    /**
     * Test that UpdateUserAction updates a user and returns the updated entity.
     *
     * This test verifies that when valid update data is provided, the action
     * processes the changes and returns the updated user with the new values.
     *
     * @return void
     */
    public function testUpdateUserAction(): void
    {
        $data = [
            'first_name' => 'Alice',
            'last_name' => 'Johnson',
        ];

        $request = $this->createRequestWithBody($data);
        $response = $this->createResponse();

        $action = new UpdateUserAction($this->jsonResponder, $this->userService);
        $result = $action($request, $response, ['id' => '1']);

        $responseData = $this->assertJsonResponse($result);
        $this->assertEquals('Alice', $responseData['firstName']);
        $this->assertEquals('Johnson', $responseData['lastName']);
    }
}
