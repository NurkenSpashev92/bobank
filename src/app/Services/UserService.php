<?php

declare(strict_types=1);

namespace App\Services;

use App\Contract\UserRepositoryInterface;
use App\Models\User;

class UserService
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getPaginatedUsers(int $perPage = 10)
    {
        return $this->userRepository->getAllPaginated($perPage);
    }

    public function createUser(array $data): User
    {
        return $this->userRepository->create($data);
    }

    public function updateUser(User $user, array $data): bool
    {
        return $this->userRepository->updateUser($user, $data);
    }
}
