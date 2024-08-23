<?php

namespace App\Services\Transaction\Actions;

use App\Models\Transaction;

class CalculateCriticalRatio
{
    public function handle(Transaction $transaction): float|int
    {
        $transaction->loadMissing('service.machines');

        $weight = $transaction->weight;
        $machines = $transaction->service->machines;

        $dueDate = $transaction->service->duration * 24 * 60;
        $processingTime = 0;

        foreach ($machines as $machine) {
            $usage = ceil($weight / $machine->capacity);
            $duration = $usage * $machine->duration; // in minutes

            $processingTime += $duration;
        }

        return $dueDate / $processingTime;
    }
}
