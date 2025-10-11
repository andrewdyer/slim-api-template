<?php

declare(strict_types=1);

namespace App\Domains\User\Services;

use App\Domains\User\Exceptions\UserNotFoundException;
use App\Domains\User\Models\User;
use Illuminate\Database\Eloquent\Collection;
use InvalidArgumentException;

class UserService
{
    public function create(array $data): User
    {
        if (!array_key_exists('first_name', $data) || empty($data['first_name'])) {
            throw new InvalidArgumentException('First name is required');
        }

        if (!array_key_exists('last_name', $data) || empty($data['last_name'])) {
            throw new InvalidArgumentException('Last name is required');
        }

        $user = new User();
        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];
        $user->save();

        return $user;
    }

    public function update(int $id, array $data): User
    {
        $user = $this->getById($id);

        if (array_key_exists('first_name', $data)) {
            $user->first_name = $data['first_name'];
        }

        if (array_key_exists('last_name', $data)) {
            $user->last_name = $data['last_name'];
        }

        $user->save();

        return $user;
    }

    public function delete(int $id): bool
    {
        $user = $this->getById($id);

        return (bool)$user->delete();
    }

    public function getAll(): Collection
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
