<?php

use App\Domain\Users\Repositories\UserRepository;
use App\Infrastructure\Persistence\Users\Repositories\InMemoryUserRepository;
use DI\Container;

return function (Container $container) {
    $container->set(UserRepository::class, function () {
        return new InMemoryUserRepository();
    });
};
