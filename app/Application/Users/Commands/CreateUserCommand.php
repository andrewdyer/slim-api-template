<?php

declare(strict_types=1);

namespace App\Application\Users\Commands;

/**
 * Carries the data required to create a new user.
 */
final readonly class CreateUserCommand
{
    /**
     * Creates a new CreateUserCommand.
     *
     * @param string $firstName The user's first name.
     * @param string $lastName  The user's last name.
     * @param string $email     The user's email address.
     */
    public function __construct(
        public string $firstName,
        public string $lastName,
        public string $email,
    ) {
    }
}
