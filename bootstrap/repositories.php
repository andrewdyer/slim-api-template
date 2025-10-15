<?php

use App\Domain\Users\Repositories\UserRepository;
use App\Infrastructure\Persistence\Users\Repositories\InMemoryUserRepository;
use DI\Container;

/**
 * Register repository implementations in the dependency injection container.
 *
 * This file binds abstract repository interfaces to their concrete
 * implementations, allowing the application to use dependency injection
 * for data persistence layer components.
 *
 * @param DI\Container $container The dependency injection container
 * @return void
 */
return function (Container $container) {
    $container->set(UserRepository::class, function () {
        return new InMemoryUserRepository();
    });
};
