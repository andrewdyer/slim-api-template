<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/**
 * Handles creation and removal of the users_roles pivot table.
 */
final class CreateUsersRolesTable extends AbstractMigration
{
    /**
     * Deletes the users_roles table if it exists.
     */
    public function down(): void
    {
        if ($this->hasTable('users_roles')) {
            $this->table('users_roles')->drop()->save();
        }
    }

    /**
     * Creates the users_roles table and its required columns.
     */
    public function up(): void
    {
        $this->table('users_roles', [
            'id' => false,
            'primary_key' => ['user_id', 'role_id'],
        ])
            ->addColumn('user_id', 'integer', [
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('role_id', 'integer', [
                'null' => false,
                'signed' => false,
            ])
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE'])
            ->addForeignKey('role_id', 'roles', 'id', ['delete' => 'CASCADE'])
            ->create();
    }
}
