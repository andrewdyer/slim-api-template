<?php

declare(strict_types=1);

namespace Tests\Integration\Application\Actions;

use App\Application\Actions\DeleteUserAction;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * Integration tests for DeleteUserAction.
 */
#[CoversClass(DeleteUserAction::class)]
final class DeleteUserActionTest extends AbstractUserActionTestCase
{
    /**
     * Asserts that a 204 response is returned when the user exists.
     */
    public function testReturns204WhenUserExists(): void
    {
        $user = $this->userFactory->create();

        $response = $this->request('DELETE', '/api/v1/users/' . $user->getId());

        $this->assertSame(204, $response->getStatusCode());
        $this->assertNull($this->userRepository->findById($user->getId()));
    }

    /**
     * Asserts that a 404 response is returned when the user does not exist.
     */
    public function testReturns404WhenUserDoesNotExist(): void
    {
        $user = $this->userFactory->create();

        $this->userRepository->delete($user->getId());

        $response = $this->request('DELETE', '/api/v1/users/' . $user->getId());

        $this->assertSame(404, $response->getStatusCode());
    }
}
