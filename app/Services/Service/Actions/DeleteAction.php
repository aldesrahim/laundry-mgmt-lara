<?php

namespace App\Services\Service\Actions;

use App\Exceptions\ConstraintFailedException;
use App\Models\Service;

class DeleteAction
{
    public function handle(Service $service): bool
    {
        throw_if($service->transactions()->exists(), ConstraintFailedException::class);

        $service->delete();

        return true;
    }
}
