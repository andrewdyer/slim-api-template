<?php

declare(strict_types=1);

namespace App\Application\Users\DTOs;

use App\Domain\User\User;
use JsonSerializable;

/**
 * Represents a user in outbound API responses.
 *
 * Constructed from a domain User entity and serialized to JSON in action responses.
 */
final class UserResponseDTO implements JsonSerializable
{
    /**
     * Constructs a new UserResponseDTO with the provided user details.
     *
     * @param int $id The unique identifier of the user.
     * @param string $firstName The user's first name.
     * @param string $lastName The user's last name.
     * @param string $email The user's email address.
     */
    public function __construct(
        public int $id,
        public string $firstName,
        public string $lastName,
        public string $email,
    ) {
    }

    /**
     * Creates a UserResponseDTO from a domain User entity.
     *
     * @param User $user The domain user to convert.
     * @return self A populated UserResponseDTO instance.
     */
    public static function fromDomain(User $user): self
    {
        return new self(
            id: $user->getId(),
            firstName: $user->getFirstName(),
            lastName: $user->getLastName(),
            email: $user->getEmail()
        );
    }

    /**
     * Serializes the DTO to an associative array for JSON encoding.
     *
     * @return array<string, mixed> An associative array of the DTO's public properties.
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
