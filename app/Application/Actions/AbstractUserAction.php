<?php

declare(strict_types=1);

namespace App\Application\Actions;

use AndrewDyer\Actions\AbstractAction;
use App\Application\Services\UserService;

/**
 * Handles shared dependencies for user-related HTTP actions.
 */
abstract class AbstractUserAction extends AbstractAction
{
    /**
     * Creates a new AbstractUserAction with the required dependencies.
     *
     * @param UserService $userService The service that handles user application logic.
     */
    public function __construct(protected readonly UserService $userService)
    {
    }
}
