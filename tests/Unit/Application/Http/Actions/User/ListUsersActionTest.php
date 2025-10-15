<?php

namespace Tests\Unit\Application\Http\Actions\User;

use App\Application\Http\Actions\User\ListUsersAction;

final class ListUsersActionTest extends UserActionTestCase
{
    /**
     * Test that ListUsersAction returns all users.
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
