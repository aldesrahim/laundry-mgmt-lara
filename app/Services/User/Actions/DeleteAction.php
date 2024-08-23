<?php

namespace App\Services\User\Actions;

use App\Models\User;

class DeleteAction
{
    public function handle(User $user): bool
    {
        throw_if($user->isSuperAdmin(), 'Super administrator can not be deleted');

        $user->delete();

        return true;
    }
}
