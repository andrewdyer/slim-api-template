<?php

namespace Tests\Unit\Application\Http\Actions\User;

use App\Application\Http\Responders\JsonResponder;
use App\Application\Users\Services\UserService;
use App\Infrastructure\Persistence\Users\Repositories\InMemoryUserRepository;
use Tests\Unit\Application\Http\Actions\ActionTestCase;

/**
 * Base test case for user-related HTTP action tests.
 *
 * This abstract class provides common setup and dependencies for testing
 * user actions. It creates the necessary service and responder instances
 * with a test-appropriate in-memory repository.
 */
abstract class UserActionTestCase extends ActionTestCase
{
    /**
     * JSON responder for formatting test responses.
     *
     * @var JsonResponder
     */
    protected JsonResponder $jsonResponder;

    /**
     * User service with in-memory repository for testing.
     *
     * @var UserService
     */
    protected UserService $userService;

    /**
     * Set up test dependencies before each test method.
     *
     * This method initializes the user service with an in-memory repository
     * and creates a JSON responder instance for use in action tests.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->userService = new UserService(new InMemoryUserRepository());
        $this->jsonResponder = new JsonResponder();
    }
}
