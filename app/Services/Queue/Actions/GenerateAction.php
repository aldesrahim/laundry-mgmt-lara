<?php

namespace App\Services\Queue\Actions;

use App\Exceptions\NoPendingTransactionsException;
use App\Models\Queue;
use App\Models\Transaction;
use App\Models\User;

class GenerateAction
{
    public function handle(User $user): void
    {
        $this->checkPendingTransactions();

        $pendingTransactions = Transaction::query()
            ->where('queued', 0)
            ->orderBy('critical_ratio');

        $batch = Queue::max('batch') + 1;
        $order = 1;

        /** @var Transaction $item */
        foreach ($pendingTransactions->cursor() as $transaction) {
            $transaction->fill(['queued' => 1, 'queued_at' => now()])->save();

            Queue::create([
                'user_id' => $user->id,
                'transaction_id' => $transaction->id,
                'batch' => $batch,
                'order' => $order++,
            ]);
        }
    }

    protected function checkPendingTransactions(): void
    {
        $exists = Transaction::query()
            ->where('queued', 0)
            ->exists();

        throw_unless($exists, NoPendingTransactionsException::class);
    }
}
