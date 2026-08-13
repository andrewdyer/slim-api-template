<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/**
 * Handles creation and removal of the permissions table.
 */
final class CreatePermissionsTable extends AbstractMigration
{
    /**
     * Deletes the permissions table if it exists.
     */
    public function down(): void
    {
        if ($this->hasTable('permissions')) {
            $this->table('permissions')->drop()->save();
        }
    }

    /**
     * Creates the permissions table and its required columns.
     */
    public function up(): void
    {
        $this->table('permissions')
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
