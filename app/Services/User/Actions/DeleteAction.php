<?php

namespace App\Services\User\Actions;

use App\Exceptions\ConstraintFailedException;
use App\Models\User;

class DeleteAction
{
    public function handle(User $user): bool
    {
        throw_if($user->isSuperAdmin(), 'Super administrator can not be deleted');

        $constraints = collect([
            'transactions',
            'queues',
        ]);

        $constraints->each(function ($relation) use ($user) {
            throw_if($user->{$relation}()->exists(), ConstraintFailedException::class);
        });

        $user->delete();

        return true;
    }
}
