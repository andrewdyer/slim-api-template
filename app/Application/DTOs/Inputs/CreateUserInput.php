<?php

declare(strict_types=1);

namespace App\Application\DTOs\Inputs;

use AndrewDyer\Actions\Exceptions\BadRequestException;

/**
 * Carries the validated input data required to create a new user.
 */
final class CreateUserInput
{
    /**
     * Creates a new CreateUserInput.
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
     * Creates a CreateUserInput from a raw associative array, typically sourced from a request body.
     *
     * @param  array<string, mixed> $data The raw input data.
     * @return self                 A populated CreateUserInput instance.
     * @throws BadRequestException  If any of the required fields (first_name, last_name, email) are missing,
     *                              or if the email address is not a validly formatted email address.
     */
    public static function fromArray(array $data): self
    {
        if (!isset($data['first_name']) || !isset($data['last_name']) || !isset($data['email'])) {
            throw new BadRequestException('Missing required fields');
        }

        $email = (string)$data['email'];

        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw new BadRequestException('Invalid email address');
        }

        return new self(
            firstName: (string)$data['first_name'],
            lastName: (string)$data['last_name'],
            email: $email
        );
    }
}
