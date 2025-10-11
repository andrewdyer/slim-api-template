<?php

declare(strict_types=1);

namespace App\Domain\User\Services;

use App\Domain\User\Exceptions\UserNotFoundException;
use App\Domain\User\Models\User;
use Illuminate\Database\Eloquent\Collection;
use InvalidArgumentException;

class UserService
{
    public function create(array $data): User
    {
        if (!isset($data['first_name']) || !is_string($data['first_name']) || strlen(trim($data['first_name'])) === 0) {
            throw new InvalidArgumentException('First name is required');
        }

        if (!isset($data['last_name']) || !is_string($data['last_name']) || strlen(trim($data['last_name'])) === 0) {
            throw new InvalidArgumentException('Last name is required');
        }

        $user = new User();
        $user->first_name = trim($data['first_name']);
        $user->last_name = trim($data['last_name']);
        $user->save();

        return $user;
    }

    public function update(int $id, array $data): User
    {
        $user = $this->getById($id);

        if (array_key_exists('first_name', $data)) {
            if (!is_string($data['first_name']) || strlen(trim($data['first_name'])) === 0) {
                throw new InvalidArgumentException('First name is required');
            }
            $user->first_name = trim($data['first_name']);
        }

        if (array_key_exists('last_name', $data)) {
            if (!is_string($data['last_name']) || strlen(trim($data['last_name'])) === 0) {
                throw new InvalidArgumentException('Last name is required');
            }
            $user->last_name = trim($data['last_name']);
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
