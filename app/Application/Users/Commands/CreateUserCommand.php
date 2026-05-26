<?php

declare(strict_types=1);

namespace App\Application\Users\Commands;

use AndrewDyer\CommandBus\Contracts\CommandInterface;

readonly class CreateUserCommand implements CommandInterface
{
    public function __construct(
        public string $firstName,
        public string $lastName,
        public string $email,
    ) {
    }
}
