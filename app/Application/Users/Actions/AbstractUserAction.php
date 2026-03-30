<?php

declare(strict_types=1);

namespace App\Application\Users\Actions;

use AndrewDyer\Actions\AbstractAction;
use App\Application\Users\Services\UserService;

/**
 * Base class for all user-related HTTP actions.
 *
 * Provides shared dependencies to concrete action classes via constructor injection.
 */
abstract class AbstractUserAction extends AbstractAction
{
    /**
     * Injects the user service used by all user actions.
     *
     * @param UserService $userService The service that handles user application logic.
     */
    public function __construct(protected readonly UserService $userService)
    {
    }
}
