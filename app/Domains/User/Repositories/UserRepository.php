<?php

declare(strict_types=1);

namespace App\Domains\User\Repositories;

use App\Domains\User\Models\User;
use Illuminate\Support\Arr;

class UserRepository
{
    public function createUser(array $data)
    {
        $user = new User();
        $user->first_name = Arr::get($data, 'first_name');
        $user->last_name = Arr::get($data, 'last_name');
        $user->save();

        return $user;
    }

    public function deleteUser(int $id): void
    {
        $user = User::findOrFail($id);
        $user->delete();
    }

    public function getUsers()
    {
        return User::all();
    }

    public function getUserById(int $id)
    {
        return User::findOrFail($id);
    }

    public function updateUser(int $id, array $data)
    {
        $user = User::findOrFail($id);

        if ($firstName = Arr::get($data, 'first_name')) {
            $user->first_name = $firstName;
        }

        if ($lastName = Arr::get($data, 'last_name')) {
            $user->last_name = $lastName;
        }

        $user->save();

        return $user;
    }
}
