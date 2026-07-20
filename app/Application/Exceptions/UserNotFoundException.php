<?php

declare(strict_types=1);

namespace App\Application\Exceptions;

use AndrewDyer\Actions\Exceptions\NotFoundException;

/**
 * Thrown when a user cannot be found by the given identifier.
 */
class UserNotFoundException extends NotFoundException
{
}
