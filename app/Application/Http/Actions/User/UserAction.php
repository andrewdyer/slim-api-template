<?php

namespace App\Application\Http\Actions\User;

use App\Application\Http\Actions\Action;
use App\Application\Http\Responders\JsonResponder;
use App\Application\Users\Services\UserService;

abstract class UserAction extends Action
{
    public function __construct(protected readonly JsonResponder $jsonResponder, protected readonly UserService $userService)
    {
    }
}
