<?php

namespace App\Application\Users\Exceptions;

/**
 * Exception thrown when a requested user cannot be found.
 *
 * This exception is typically thrown by the UserService when attempting
 * to retrieve a user by ID that does not exist in the system.
 */
class UserNotFoundException extends \Exception
{
}
