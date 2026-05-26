<?php

declare(strict_types=1);

namespace App\Application\Users\Commands;

use AndrewDyer\CommandBus\Contracts\CommandInterface;

readonly class DeleteUserCommand implements CommandInterface
{
    public function __construct(
        public int $id,
    ) {
    }
}
