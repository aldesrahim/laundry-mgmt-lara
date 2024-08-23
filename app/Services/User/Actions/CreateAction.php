<?php

namespace App\Services\User\Actions;

use App\Models\User;
use App\Services\User\Level;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CreateAction
{
    public function handle(array $payload): User
    {
        $validated = Validator::validate($payload, [
            'username' => ['required', Rule::unique(User::class, 'username')],
            'password' => ['required'],
            'name' => ['required'],
            'level' => ['required', Rule::enum(Level::class)],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        return User::create($validated);
    }
}
