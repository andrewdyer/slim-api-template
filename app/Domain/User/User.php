<?php

declare(strict_types=1);

namespace App\Domain\User;

use JsonSerializable;

/**
 * Carries the data required to represent a user in the domain layer.
 */
final readonly class User implements JsonSerializable
{
    /**
     * Creates a new User.
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
     * Returns an associative array representation of the User for JSON encoding.
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
