<?php

declare(strict_types=1);

namespace App\Application\Users\Commands;

/**
 * Carries the data required to update an existing user.
 */
final readonly class UpdateUserCommand
{
    /**
     * Creates a new UpdateUserCommand.
     *
     * @param int         $id        The ID of the user to update.
     * @param string|null $firstName The updated first name, or null to leave unchanged.
     * @param string|null $lastName  The updated last name, or null to leave unchanged.
     * @param string|null $email     The updated email address, or null to leave unchanged.
     */
    public function __construct(
        public int $id,
        public ?string $firstName = null,
        public ?string $lastName = null,
        public ?string $email = null,
    ) {
    }
}
