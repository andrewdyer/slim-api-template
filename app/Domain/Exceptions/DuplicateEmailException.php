<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

use RuntimeException;

/**
 * Thrown when a repository operation would violate the uniqueness of a user's email address.
 */
class DuplicateEmailException extends RuntimeException
{
}
