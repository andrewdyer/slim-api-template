<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Http\Actions\User;

use App\Application\Http\Actions\User\DeleteUserAction;

/**
 * Test cases for the DeleteUserAction HTTP action.
 *
 * This test class verifies that the delete user action correctly handles
 * user deletion requests and returns appropriate HTTP responses.
 */
final class DeleteUserActionTest extends UserActionTestCase
{
    /**
     * Test that DeleteUserAction successfully deletes a user and returns 204.
     *
     * This test verifies that when a valid user ID is provided, the action
     * processes the deletion and returns a 204 No Content status code.
     *
     * @return void
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
