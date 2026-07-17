<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

/**
 * Handles seeding the users table with an example user.
 */
final class UserSeeder extends AbstractSeed
{
    /**
     * Creates the example user record.
     */
    public function run(): void
    {
        $this->table('users')
            ->insert([
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@example.com',
            ])
            ->saveData();
    }
}
