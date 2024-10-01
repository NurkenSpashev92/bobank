<?php

namespace App\Contract;

use App\Models\User;

interface UserRepositoryInterface
{
    public function updateUser(User $user, array $data): bool;
}
