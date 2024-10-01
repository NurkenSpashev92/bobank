<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use App\Contract\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function getAllPaginated($perPage = 10)
    {
        return User::paginate($perPage);
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function updateUser(User $user, array $data): bool
    {
        return $user->update($data);
    }
}
