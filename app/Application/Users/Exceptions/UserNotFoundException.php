<?php

declare(strict_types=1);

namespace App\Application\Users\Exceptions;

use Exception;

/**
 * Thrown when a user cannot be found by the given identifier.
 */
class UserNotFoundException extends Exception
{
}
