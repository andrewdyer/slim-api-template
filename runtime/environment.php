<?php

declare(strict_types=1);

use Dotenv\Dotenv;

return function(): void {
    if (!get_env('APP_ENV')) {
        Dotenv::createImmutable(root_path('/'))->load();
    }
};
