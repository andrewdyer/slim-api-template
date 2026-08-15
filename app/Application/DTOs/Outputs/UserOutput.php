<?php

declare(strict_types=1);

namespace App\Application\DTOs\Outputs;

use App\Domain\Models\User;
use JsonSerializable;
use OpenApi\Attributes as OA;

/**
 * Carries user data for outbound API responses.
 */
#[OA\Schema(
    schema: 'UserOutput',
    required: ['id', 'firstName', 'lastName', 'email'],
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'firstName', type: 'string', example: 'Jane'),
        new OA\Property(property: 'lastName', type: 'string', example: 'Doe'),
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'jane.doe@example.com'),
    ],
    type: 'object'
)]
final class UserOutput implements JsonSerializable
{
    /**
     * Creates a new UserOutput.
     *
     * @param int    $id        The unique identifier of the user.
     * @param string $firstName The user's first name.
     * @param string $lastName  The user's last name.
     * @param string $email     The user's email address.
     */
    public function __construct(
        public int $id,
        public string $firstName,
        public string $lastName,
        public string $email,
    ) {
    }

    /**
     * Creates a UserOutput from a domain User entity.
     *
     * @param  User $user The domain user to convert.
     * @return self A populated UserOutput instance.
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
     * Returns an associative array representation for JSON encoding.
     *
     * @return array<string, mixed> An associative array of the output's public properties.
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
