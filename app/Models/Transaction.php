<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'service_id',
        'date',
        'target_date',
        'customer',
        'weight',
        'total',
        'queued',
        'queued_at',
        'critical_ratio',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'datetime',
            'target_date' => 'date',
        ];
    }

    public function scopeUnQueued(Builder $query): Builder
    {
        return $query->where('queued', 0);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function queue(): HasOne
    {
        return $this->hasOne(Queue::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
