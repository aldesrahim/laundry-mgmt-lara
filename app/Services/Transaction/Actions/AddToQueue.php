<?php

namespace App\Services\Transaction\Actions;

use App\Models\Queue;
use App\Models\Transaction;

class AddToQueue
{
    public function handle(Transaction $transaction): void
    {
        if (Queue::unfinished()->exists()) {
            return;
        }

        $transaction->fill(['queued' => 1, 'queued_at' => now()])->save();

        Queue::create([
            'user_id' => $transaction->user_id,
            'transaction_id' => $transaction->id,
            'batch' => Queue::max('batch') + 1,
            'order' => 1,
        ]);
    }
}
