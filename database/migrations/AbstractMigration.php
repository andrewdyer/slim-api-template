<?php

namespace Database\Migrations;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Builder;
use Phinx\Migration\AbstractMigration as BaseAbstractMigration;

abstract class AbstractMigration extends BaseAbstractMigration
{
    private Builder $schema;

    protected function getSchema(): Builder
    {
        return $this->schema;
    }

    public function init(): void
    {
        $adapter = $this->getAdapter();

        $capsule = new Capsule();
        $capsule->addConnection([
            'driver' => $adapter->getOption('adapter'),
            'host' => $adapter->getOption('host'),
            'port' => $adapter->getOption('port'),
            'database' => $adapter->getOption('name'),
            'username' => $adapter->getOption('user'),
            'password' => $adapter->getOption('pass'),
            'charset' => $adapter->getOption('charset'),
            'collation' => $adapter->getOption('collation'),
            'prefix' => $adapter->getOption('prefix'),
        ]);
        $capsule->setAsGlobal();

        $this->schema = $capsule->schema();
    }
}
