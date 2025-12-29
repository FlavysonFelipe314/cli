<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FiscalObligation extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'periodicity',
        'scope',
        'due_day',
        'responsible',
        'description',
        'status',
        'last_completed_at',
        'next_due_date',
    ];

    protected $casts = [
        'due_day' => 'integer',
        'last_completed_at' => 'date',
        'next_due_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
