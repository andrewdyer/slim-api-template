<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Http\Actions\User;

use App\Application\Http\Actions\User\ListUsersAction;

/**
 * Test cases for the ListUsersAction HTTP action.
 *
 * This test class verifies that the list users action correctly retrieves
 * all users and returns them in the expected JSON format.
 */
final class ListUsersActionTest extends UserActionTestCase
{
    /**
     * Test that ListUsersAction returns all users.
     *
     * This test verifies that the action retrieves all users from the repository
     * and returns them as a JSON array with the expected count.
     *
     * @return void
     */
    public function testListUsersAction(): void
    {
        $request = $this->createRequestWithBody([]);
        $response = $this->createResponse();

        $action = new ListUsersAction($this->jsonResponder, $this->userService);
        $result = $action($request, $response, []);

        $responseData = $this->assertJsonResponse($result);
        $this->assertCount(5, $responseData);
    }
}
