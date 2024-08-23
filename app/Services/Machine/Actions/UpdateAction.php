<?php

namespace App\Services\Machine\Actions;

use App\Models\Machine;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UpdateAction
{
    public function handle(Machine $machine, array $payload): Machine
    {
        $validated = Validator::validate($payload, [
            'type' => ['required', Rule::unique(Machine::class, 'type')->ignoreModel($machine)],
            'capacity' => ['required', 'numeric', 'min:1'],
            'duration' => ['required', 'numeric', 'min:1'],
        ]);

        $machine->fill($validated)->save();

        return $machine;
    }
}
