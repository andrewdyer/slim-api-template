<?php

namespace Tests\Unit\Application\Http\Actions\User;

use App\Application\Http\Actions\User\ShowUserAction;

final class ShowUserActionTest extends UserActionTestCase
{
    /**
     * Test that ShowUserAction returns a single user by ID.
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
