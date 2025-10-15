<?php

namespace Tests\Unit\Application\Http\Actions\User;

use App\Application\Http\Responders\JsonResponder;
use App\Application\Users\Services\UserService;
use App\Infrastructure\Persistence\Users\Repositories\InMemoryUserRepository;
use Tests\Unit\Application\Http\Actions\ActionTestCase;

abstract class UserActionTestCase extends ActionTestCase
{
    protected JsonResponder $jsonResponder;
    protected UserService $userService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userService = new UserService(new InMemoryUserRepository());
        $this->jsonResponder = new JsonResponder();
    }
}
