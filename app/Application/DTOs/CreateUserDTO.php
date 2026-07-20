<?php

declare(strict_types=1);

namespace App\Application\DTOs;

use InvalidArgumentException;

/**
 * Carries the validated input data required to create a new user.
 */
final class CreateUserDTO
{
    /**
     * Creates a new CreateUserDTO.
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

    /**
     * Creates a CreateUserDTO from a raw associative array, typically sourced from a request body.
     *
     * @param  array<string, mixed>     $data The raw input data.
     * @return self                     A populated CreateUserDTO instance.
     * @throws InvalidArgumentException If any of the required fields (first_name, last_name, email) are missing.
     */
    public static function fromArray(array $data): self
    {
        if (!isset($data['first_name']) || !isset($data['last_name']) || !isset($data['email'])) {
            throw new InvalidArgumentException('Missing required fields');
        }

        return new self(
            firstName: (string)$data['first_name'],
            lastName: (string)$data['last_name'],
            email: (string)$data['email']
        );
    }
}
