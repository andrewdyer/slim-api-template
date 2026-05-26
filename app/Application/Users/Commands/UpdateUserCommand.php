<?php

declare(strict_types=1);

namespace App\Application\Users\Commands;

use AndrewDyer\CommandBus\Contracts\CommandInterface;

readonly class UpdateUserCommand implements CommandInterface
{
    public function __construct(
        public int $id,
        public ?string $firstName = null,
        public ?string $lastName = null,
        public ?string $email = null,
    ) {
    }
}
