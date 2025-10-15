<?php

namespace Tests\Unit\Application\Http\Actions\User;

use App\Application\Http\Actions\User\ShowUserAction;

/**
 * Test cases for the ShowUserAction HTTP action.
 *
 * This test class verifies that the show user action correctly retrieves
 * a specific user by ID and returns the user data in JSON format.
 */
final class ShowUserActionTest extends UserActionTestCase
{
    /**
     * Test that ShowUserAction returns a single user by ID.
     *
     * This test verifies that when a valid user ID is provided, the action
     * retrieves the specific user and returns their data with the correct ID.
     *
     * @return void
     */
    public function testShowUserAction(): void
    {
        $request = $this->createRequestWithBody([]);
        $response = $this->createResponse();

        $action = new ShowUserAction($this->jsonResponder, $this->userService);
        $result = $action($request, $response, ['id' => '1']);

        $responseData = $this->assertJsonResponse($result);
        $this->assertEquals(1, $responseData['id']);
    }
}
