<?php

namespace App\Services\Transaction\Actions;

use App\Models\Service;
use App\Models\Transaction;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CreateAction
{
    public const SPARE_HOURS = 4;

    public function __construct(
        protected AddToQueue $queue,
        protected CalculateCriticalRatio $calculation,
    ) {
    }

    public function handle(array $payload): Transaction
    {
        $validated = Validator::validate($payload, [
            'user_id' => ['required', Rule::exists(User::class, 'id')],
            'service_id' => ['required', Rule::exists(Service::class, 'id')],
            'date' => ['required', 'date'],
            'customer' => ['required', 'string'],
            'weight' => ['required', 'numeric', 'min:1'],
        ]);

        $service = $this->resolveServiceId($validated['service_id']);

        $transaction = new Transaction($validated);
        $transaction->target_date = $this->calculateTarget($transaction->date, $service->duration);
        $transaction->total = $service->price * $validated['weight'];
        $transaction->critical_ratio = $this->calculation->handle($transaction);
        $transaction->save();

        $this->queue->handle($transaction);

        return $transaction;
    }

    protected function resolveServiceId(string $id): Service
    {
        return Service::findOrFail($id);
    }

    protected function calculateTarget(CarbonInterface $date, int $days): string
    {
        return $date->clone()
            ->addDays($days)
            ->addHours(static::SPARE_HOURS)
            ->startOfDay()
            ->toDateString();
    }
}
