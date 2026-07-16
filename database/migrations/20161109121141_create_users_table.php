<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/**
 * Creates the users table used by the application persistence layer.
 */
final class CreateUsersTable extends AbstractMigration
{
    /**
     * Applies the migration by creating the users table and its required columns.
     *
     * @return void
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

    /**
     * Reverts the migration by dropping the users table if it exists.
     *
     * @return void
     */
    public function down(): void
    {
        if ($this->hasTable('users')) {
            $this->table('users')->drop()->save();
        }
    }
}
