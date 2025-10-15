<?php

namespace App\Application\Users\DTO;

/**
 * Data Transfer Object for creating a new User.
 *
 * This class encapsulates the data required to create a user
 * and provides a convenient static constructor (`fromArray`)
 * for transforming associative arrays (e.g. request body data)
 * into a strongly-typed DTO instance.
 */
final class CreateUserDTO
{
    /**
     * @param string $firstName the user's first name
     * @param string $lastName  the user's last name
     * @param string $email     the user's email address
     */
    public function __construct(
        public string $firstName,
        public string $lastName,
        public string $email,
    ) {
    }

    /**
     * Create a new DTO instance from an associative array.
     *
     * This is typically used to transform incoming request data
     * (such as parsed JSON) into a CreateUserDTO.
     *
     * @param array{
     *     first_name: string,
     *     last_name: string,
     *     email: string
     * } $data The input data, typically from the request body
     *
     * @return self a populated CreateUserDTO instance
     *
     * @throws \InvalidArgumentException if any required fields are missing
     */
    public static function fromArray(array $data): self
    {
        if (!isset($data['first_name']) || !isset($data['last_name']) || !isset($data['email'])) {
            throw new \InvalidArgumentException('Missing required fields');
        }

        return new self(
            firstName: (string) $data['first_name'],
            lastName: (string) $data['last_name'],
            email: (string) $data['email']
        );
    }
}
