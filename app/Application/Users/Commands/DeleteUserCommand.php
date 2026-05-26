<?php

declare(strict_types=1);

namespace App\Application\Users\Commands;

readonly class DeleteUserCommand
{
    public function __construct(
        public int $id,
    ) {
    }
}
