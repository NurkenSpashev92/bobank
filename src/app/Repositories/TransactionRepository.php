<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Contract\TransactionRepositoryInterface;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function deposit(User $user, float $amount): bool
    {
        if ($amount <= 0) {
            throw new \Exception("Deposit amount must be positive.");
        }

        $user->balance += $amount;
        return $user->save();
    }

    public function transfer(User $sender, User $recipient, float $amount): bool
    {
        if ($amount <= 0) {
            throw new \Exception("Transfer amount must be positive.");
        }

        if ($sender->balance < $amount) {
            throw new \Exception("Insufficient balance for transfer.");
        }

        return DB::transaction(function () use ($sender, $recipient, $amount) {
            $sender->balance -= $amount;
            $recipient->balance += $amount;

            $sender->save();
            $recipient->save();

            return true;
        });
    }
}
