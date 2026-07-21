<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/**
 * Handles creation and removal of the users table.
 */
final class CreateUsersTable extends AbstractMigration
{
    /**
     * Deletes the users table if it exists.
     */
    public function down(): void
    {
        if ($this->hasTable('users')) {
            $this->table('users')->drop()->save();
        }
    }

    /**
     * Creates the users table and its required columns.
     */
    public function up(): void
    {
        $this->table('users')
            ->addColumn('first_name', 'string')
            ->addColumn('last_name', 'string')
            ->addColumn('email', 'string')
            ->addIndex(['email'], ['unique' => true])
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
