<?php

declare(strict_types=1);

namespace App\Application\Users\DTOs;

/**
 * Carries the partial input data required to update an existing user.
 *
 * Used by UpdateUserAction to pass the parsed request body to UserService.
 * All fields except the user ID are optional to support partial updates.
 */
final class UpdateUserDTO
{
    /**
     * Constructs a new UpdateUserDTO for the specified user.
     *
     * @param int         $id        The unique identifier of the user to update.
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

    /**
     * Creates an UpdateUserDTO from a raw associative array, typically sourced from a request body.
     *
     * @param  int                  $id   The unique identifier of the user to update.
     * @param  array<string, mixed> $data The raw input data. Unset keys default to null.
     * @return self                 A populated UpdateUserDTO instance.
     */
    public static function fromArray(int $id, array $data): self
    {
        return new self(
            id: $id,
            firstName: isset($data['first_name']) ? (string)$data['first_name'] : null,
            lastName: isset($data['last_name']) ? (string)$data['last_name'] : null,
            email: isset($data['email']) ? (string)$data['email'] : null,
        );
    }
}
