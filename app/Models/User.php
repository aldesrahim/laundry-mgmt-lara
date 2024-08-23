<?php

namespace App\Models;

use App\Services\User\Level;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'name',
        'username',
        'password',
        'level',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'level' => Level::class,
        ];
    }

    public function isSuperAdmin(): bool
    {
        return $this->id === 1;
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
