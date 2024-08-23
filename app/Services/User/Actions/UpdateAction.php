<?php

namespace App\Services\User\Actions;

use App\Models\User;
use App\Services\User\Level;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UpdateAction
{
    public function handle(User $user, array $payload): User
    {
        $validated = Validator::validate($payload, [
            'username' => ['required', Rule::unique(User::class, 'username')->ignoreModel($user)],
            'password' => ['nullable'],
            'name' => ['required'],
            'level' => ['required', Rule::enum(Level::class)],
        ]);

        $validated['password'] = filled($validated['password'] ?? null)
            ? Hash::make($validated['password'])
            : $user->password;

        $validated = $this->mutateDataBeforeFill($user, $validated);

        $user->fill($validated)->save();

        return $user;
    }

    protected function mutateDataBeforeFill(User $user, array $validated): array
    {
        if (!$user->isSuperAdmin()) {
            return $validated;
        }

        $validated['level'] = $user->level;

        return $validated;
    }
}
