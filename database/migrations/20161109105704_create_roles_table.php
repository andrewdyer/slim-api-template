<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/**
 * Handles creation and removal of the roles table.
 */
final class CreateRolesTable extends AbstractMigration
{
    /**
     * Deletes the roles table if it exists.
     */
    public function down(): void
    {
        if ($this->hasTable('roles')) {
            $this->table('roles')->drop()->save();
        }
    }

    /**
     * Creates the roles table and its required columns.
     */
    public function up(): void
    {
        $this->table('roles')
            ->addColumn('name', 'string', ['limit' => 120])
            ->addColumn('description', 'string', ['limit' => 255])
            ->addIndex(['name'], ['unique' => true])
            ->addColumn('created_at', 'timestamp', [
                'default' => 'CURRENT_TIMESTAMP',
            ])
            ->addColumn('updated_at', 'timestamp', [
                'default' => 'CURRENT_TIMESTAMP',
                'update' => 'CURRENT_TIMESTAMP',
            ])
            ->create();
    }
}
