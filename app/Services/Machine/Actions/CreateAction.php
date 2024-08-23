<?php

namespace App\Services\Machine\Actions;

use App\Models\Machine;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CreateAction
{
    public function handle(array $payload): Machine
    {
        $validated = Validator::validate($payload, [
            'type' => ['required', Rule::unique(Machine::class, 'type')],
            'capacity' => ['required', 'numeric', 'min:1'],
            'duration' => ['required', 'numeric', 'min:1'],
        ]);

        return Machine::create($validated);
    }
}
