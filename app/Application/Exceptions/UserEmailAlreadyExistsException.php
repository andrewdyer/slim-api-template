<?php

declare(strict_types=1);

namespace App\Application\Exceptions;

use AndrewDyer\Actions\Exceptions\ConflictException;

/**
 * Thrown when a user cannot be created or updated because the given email is already in use.
 */
class UserEmailAlreadyExistsException extends ConflictException
{
}
