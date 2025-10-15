<?php

namespace Tests\Unit\Application\Http\Actions\User;

use App\Application\Http\Actions\User\DeleteUserAction;

final class DeleteUserActionTest extends UserActionTestCase
{
    /**
     * Test that DeleteUserAction successfully deletes a user and returns 204.
     */
    public function testDeleteUserAction(): void
    {
        $request = $this->createRequestWithBody([]);
        $response = $this->createResponse();

        $action = new DeleteUserAction($this->jsonResponder, $this->userService);
        $result = $action($request, $response, ['id' => '1']);

        $this->assertEquals(204, $result->getStatusCode());
    }
}
