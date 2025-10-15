<?php

namespace App\Application\Http\Actions\User;

use App\Application\Http\Actions\Action;
use App\Application\Http\Responders\JsonResponder;
use App\Application\Users\Services\UserService;

/**
 * Base class for all user-related HTTP actions.
 *
 * This abstract class provides common dependencies and functionality for
 * actions that handle user operations such as creation, retrieval, updating,
 * and deletion. It extends the base Action class with user-specific services.
 */
abstract class UserAction extends Action
{
    /**
     * Create a new UserAction instance.
     *
     * @param JsonResponder $jsonResponder Service for formatting JSON responses
     * @param UserService   $userService   Service for user business logic operations
     */
    public function __construct(protected readonly JsonResponder $jsonResponder, protected readonly UserService $userService)
    {
    }
}
