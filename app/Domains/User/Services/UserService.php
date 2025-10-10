<?php

declare(strict_types=1);

namespace App\Domains\User\Services;

use App\Domains\User\Exceptions\UserNotFoundException;
use App\Domains\User\Models\User;
use Illuminate\Support\Arr;

class UserService
{
    public function create(array $data): User
    {
        $user = new User();
        $user->first_name = Arr::get($data, 'first_name');
        $user->last_name = Arr::get($data, 'last_name');
        $user->save();

        return $user;
    }

    public function update(int $id, array $data): User
    {
        $user = $this->getById($id);

        if ($firstName = Arr::get($data, 'first_name')) {
            $user->first_name = $firstName;
        }

        if ($lastName = Arr::get($data, 'last_name')) {
            $user->last_name = $lastName;
        }

        $user->save();

        return $user;
    }

    public function delete(int $id): bool
    {
        $user = $this->getById($id);

        return (bool)$user->delete();
    }

    public function getAll()
    {
        return User::all();
    }

    public function getById(int $id): User
    {
        $user = User::find($id);

        if (!$user) {
            throw new UserNotFoundException($id);
        }

        return $user;
    }
}
