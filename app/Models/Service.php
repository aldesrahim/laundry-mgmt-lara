<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Service extends Model
{
    protected $fillable = [
        'name',
        'duration',
        'price',
    ];

    public function machines(): BelongsToMany
    {
        return $this
            ->belongsToMany(Machine::class)
            ->using(MachineService::class)
            ->withTimestamps();
    }
}
