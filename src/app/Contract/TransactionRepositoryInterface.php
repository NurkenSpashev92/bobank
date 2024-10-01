<?php

namespace App\Contract;

use App\Models\User;

interface TransactionRepositoryInterface
{
    public function deposit(User $user, float $amount): bool;

    public function transfer(User $sender, User $recipient, float $amount): bool;
}
