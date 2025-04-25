<?php

declare(strict_types=1);

namespace Database\Migrations;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Builder;
use Phinx\Migration\AbstractMigration as BaseAbstractMigration;

abstract class AbstractMigration extends BaseAbstractMigration
{
    private readonly Builder $schema;

    protected function getSchema(): Builder
    {
        return $this->schema;
    }

    public function init(): void
    {
        $adapter = $this->getAdapter();

        $connection = [
            'driver' => $adapter->getOption('adapter'),
            'host' => $adapter->getOption('host'),
            'port' => $adapter->getOption('port'),
            'database' => $adapter->getOption('name'),
            'username' => $adapter->getOption('user'),
            'password' => $adapter->getOption('pass'),
            'charset' => $adapter->getOption('charset'),
            'collation' => $adapter->getOption('collation'),
            'prefix' => $adapter->getOption('prefix'),
        ];

        $capsule = new Capsule();
        $capsule->addConnection($connection);
        $capsule->setAsGlobal();

        $this->schema = $capsule->schema();
    }
}
