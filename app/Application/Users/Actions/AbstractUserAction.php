<?php

declare(strict_types=1);

namespace App\Application\Users\Actions;

use AndrewDyer\Actions\AbstractAction;
use App\Application\Users\Services\UserService;

/**
 * Base class for all user-related HTTP actions.
 */
abstract class AbstractUserAction extends AbstractAction
{
    /**
     * Creates a new action with the required dependencies.
     *
     * @param UserService $userService The service that handles user application logic.
     */
    public function __construct(protected readonly UserService $userService)
    {
    }
}
