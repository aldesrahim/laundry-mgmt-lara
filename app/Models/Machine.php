<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Machine extends Model
{
    protected $fillable = [
        'type',
        'capacity',
        'duration',
    ];

    public function services(): BelongsToMany
    {
        return $this
            ->belongsToMany(Service::class)
            ->using(MachineService::class)
            ->withTimestamps();
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
