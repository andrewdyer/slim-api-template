<?php

declare(strict_types=1);

namespace App\Application\Users\Commands;

/**
 * Carries the data required to delete a user.
 */
final readonly class DeleteUserCommand
{
    /**
     * Creates a new DeleteUserCommand.
     *
     * @param int $id The ID of the user to delete.
     */
    public function __construct(
        public int $id,
    ) {
    }
}
