<?php

namespace App\Policies;

use App\Models\Machine;
use App\Models\User;
use App\Services\User\Level;

class MachinePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->level !== Level::Worker;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Machine $machine): bool
    {
        return $user->level !== Level::Worker;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->level !== Level::Worker;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Machine $machine): bool
    {
        return $user->level !== Level::Worker;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Machine $machine): bool
    {
        return $user->level !== Level::Worker;
    }
}
