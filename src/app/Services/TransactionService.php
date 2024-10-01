<?php

declare(strict_types=1);

namespace App\Services;

use App\Contract\TransactionRepositoryInterface;
use App\Models\User;

class TransactionService
{
    protected TransactionRepositoryInterface $transactionRepository;

    public function __construct(TransactionRepositoryInterface $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    public function deposit(User $user, float $amount): bool
    {
        return $this->transactionRepository->deposit($user, $amount);
    }

    public function transfer(User $sender, User $recipient, float $amount): bool
    {
        return $this->transactionRepository->transfer($sender, $recipient, $amount);
    }
}
