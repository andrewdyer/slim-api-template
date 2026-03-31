<?php

declare(strict_types=1);

namespace App\Domain\User;

use JsonSerializable;

/**
 * Represents a user entity in the domain layer.
 *
 * Encapsulates the core user data and provides read-only access through getters.
 */
final readonly class User implements JsonSerializable
{
    /**
     * Constructs a User entity with the given identity and personal details.
     *
     * @param int    $id        The unique identifier of the user.
     * @param string $firstName The user's first name.
     * @param string $lastName  The user's last name.
     * @param string $email     The user's email address.
     */
    public function __construct(
        private int $id,
        private string $firstName,
        private string $lastName,
        private string $email,
    ) {
    }

    /**
     * Returns the user's email address.
     *
     * @return string The email address.
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Returns the user's first name.
     *
     * @return string The first name.
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * Returns the user's unique identifier.
     *
     * @return int The user ID.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Returns the user's last name.
     *
     * @return string The last name.
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * Serializes the User entity to an associative array for JSON encoding.
     *
     * @return array<string, mixed> An associative array containing the user's id, firstName, lastName, and email.
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'email' => $this->email,
        ];
    }
}
