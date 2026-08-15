<?php

declare(strict_types=1);

namespace App\Application\DTOs\Inputs;

use AndrewDyer\Actions\Exceptions\BadRequestException;
use OpenApi\Attributes as OA;

/**
 * Carries partial input data required to update an existing user.
 */
#[OA\Schema(
    schema: 'UpdateUserInput',
    properties: [
        new OA\Property(property: 'first_name', type: 'string', example: 'Jane', nullable: true),
        new OA\Property(property: 'last_name', type: 'string', example: 'Doe', nullable: true),
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'jane.doe@example.com', nullable: true),
    ],
    type: 'object'
)]
final class UpdateUserInput
{
    /**
     * Creates a new UpdateUserInput.
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
     * Creates an UpdateUserInput from a raw associative array, typically sourced from a request body.
     *
     * @param  int                  $id   The unique identifier of the user to update.
     * @param  array<string, mixed> $data The raw input data. Unset keys default to null.
     * @return self                 A populated UpdateUserInput instance.
     * @throws BadRequestException  If an email address is provided but is not validly formatted.
     */
    public static function fromArray(int $id, array $data): self
    {
        $email = isset($data['email']) ? (string)$data['email'] : null;

        if (null !== $email && filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw new BadRequestException('Invalid email address');
        }

        return new self(
            id: $id,
            firstName: isset($data['first_name']) ? (string)$data['first_name'] : null,
            lastName: isset($data['last_name']) ? (string)$data['last_name'] : null,
            email: $email,
        );
    }
}
