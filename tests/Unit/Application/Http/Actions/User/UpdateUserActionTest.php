<?php

namespace Tests\Unit\Application\Http\Actions\User;

use App\Application\Http\Actions\User\UpdateUserAction;

final class UpdateUserActionTest extends UserActionTestCase
{
    /**
     * Test that UpdateUserAction updates a user and returns the updated entity.
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
