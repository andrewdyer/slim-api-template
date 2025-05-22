<?php

declare(strict_types=1);

use Database\Migrations\AbstractMigration;
use Illuminate\Database\Schema\Blueprint;

final class CreateUsersTable extends AbstractMigration
{
    public function down(): void
    {
        $this->getSchema()->drop('users');
    }

    public function up(): void
    {
        $this->getSchema()->create('users', function(Blueprint $table) {
            $table->increments('id');
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable();
        });
    }
}
