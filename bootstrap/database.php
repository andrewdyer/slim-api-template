<?php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Psr\Container\ContainerInterface;

/**
 * Boots the application's database integration.
 */
return static function(ContainerInterface $container): void {
    $container->get(Capsule::class)->bootEloquent();
};
