<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

/**
 * Handles creation and removal of the roles_permissions pivot table.
 */
final class CreateRolesPermissionsTable extends AbstractMigration
{
    /**
     * Deletes the roles_permissions table if it exists.
     */
    public function down(): void
    {
        if ($this->hasTable('roles_permissions')) {
            $this->table('roles_permissions')->drop()->save();
        }
    }

    /**
     * Creates the roles_permissions table and its required columns.
     */
    public function up(): void
    {
        $this->table('roles_permissions', [
            'id' => false,
            'primary_key' => ['role_id', 'permission_id'],
        ])
            ->addColumn('role_id', 'integer', [
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('permission_id', 'integer', [
                'null' => false,
                'signed' => false,
            ])
            ->addForeignKey('role_id', 'roles', 'id', ['delete' => 'CASCADE'])
            ->addForeignKey('permission_id', 'permissions', 'id', ['delete' => 'CASCADE'])
            ->create();
    }
}
