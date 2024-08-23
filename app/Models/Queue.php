<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Queue extends Model
{
    protected $fillable = [
        'user_id',
        'transaction_id',
        'batch',
        'order',
        'finished',
        'finished_at',
    ];

    public function markAsFinished(): void
    {
        $this->forceFill(['finished' => 1, 'finished_at' => now()])->save();
    }

    public function scopeUnfinished(Builder $query): Builder
    {
        return $query->where('finished', 0);
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
