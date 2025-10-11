<?php

declare(strict_types=1);

namespace App\Domain\User\Exceptions;

use Exception;

class UserNotFoundException extends Exception
{
    public function __construct(int $id)
    {
        parent::__construct("User with id {$id} not found", 404);
    }
}
