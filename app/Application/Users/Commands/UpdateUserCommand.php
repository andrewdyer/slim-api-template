<?php

declare(strict_types=1);

namespace App\Application\Users\Commands;

readonly class UpdateUserCommand
{
    public function __construct(
        public int $id,
        public ?string $firstName = null,
        public ?string $lastName = null,
        public ?string $email = null,
    ) {
    }
}
