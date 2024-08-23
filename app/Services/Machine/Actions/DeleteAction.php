<?php

namespace App\Services\Machine\Actions;

use App\Exceptions\ConstraintFailedException;
use App\Models\Machine;

class DeleteAction
{
    public function handle(Machine $machine): bool
    {
        throw_if($machine->services()->exists(), ConstraintFailedException::class);

        $machine->delete();

        return true;
    }
}
