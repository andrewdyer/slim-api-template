<?php

namespace App\Domain\Users\Entities;

/**
 * Class User.
 *
 * Represents a user entity within the domain layer.
 *
 * This entity is immutable (`readonly`), ensuring that once constructed,
 * its state cannot be modified directly. Any updates should occur through
 * the repository layer, which will create a new instance with updated values.
 */
final readonly class User implements \JsonSerializable
{
    /**
     * @param int    $id        the unique identifier of the user
     * @param string $firstName the user's first name
     * @param string $lastName  the user's last name
     * @param string $email     the user's email address
     */
    public function __construct(
        private int $id,
        private string $firstName,
        private string $lastName,
        private string $email,
    ) {
    }

    /**
     * Get the user's email address.
     *
     * @return string The user's email address
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Get the user's first name.
     *
     * @return string The user's first name
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * Get the user's unique identifier.
     *
     * @return int The user's ID
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the user's last name.
     *
     * @return string The user's last name
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * Convert the user entity into a serializable array.
     *
     * @return array<string, mixed>
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
