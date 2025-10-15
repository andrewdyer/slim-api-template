<?php

namespace App\Application\Users\DTO;

/**
 * Data Transfer Object for updating an existing User.
 *
 * This class encapsulates the data required to update a user
 * and provides a convenient static constructor (`fromArray`)
 * for transforming associative arrays (e.g. request body data)
 * into a strongly-typed DTO instance. All fields except the ID
 * are optional to support partial updates.
 */
final class UpdateUserDTO
{
    /**
     * @param int         $id        the unique identifier of the user to update
     * @param string|null $firstName the user's first name (null to keep existing value)
     * @param string|null $lastName  the user's last name (null to keep existing value)
     * @param string|null $email     the user's email address (null to keep existing value)
     */
    public function __construct(
        public int $id,
        public ?string $firstName = null,
        public ?string $lastName = null,
        public ?string $email = null,
    ) {
    }

    /**
     * Create a new DTO instance from an associative array.
     *
     * This is typically used to transform incoming request data
     * (such as parsed JSON) into an UpdateUserDTO. Only provided
     * fields will be updated; missing fields will be null and
     * the existing values will be preserved.
     *
     * @param int $id the unique identifier of the user to update
     * @param array{
     *     first_name?: string,
     *     last_name?: string,
     *     email?: string
     * } $data The input data, typically from the request body
     *
     * @return self a populated UpdateUserDTO instance
     */
    public static function fromArray(int $id, array $data): self
    {
        return new self(
            id: $id,
            firstName: isset($data['first_name']) ? (string) $data['first_name'] : null,
            lastName: isset($data['last_name']) ? (string) $data['last_name'] : null,
            email: isset($data['email']) ? (string) $data['email'] : null,
        );
    }
}
