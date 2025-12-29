<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinancialGoal extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'type',
        'scope',
        'target_value',
        'start_date',
        'end_date',
        'description',
        'current_value',
        'status',
    ];

    protected $casts = [
        'target_value' => 'decimal:2',
        'current_value' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getProgressAttribute(): float
    {
        if ($this->target_value == 0) return 0;
        $progress = ($this->current_value / $this->target_value) * 100;
        return (float) min(100, max(0, $progress));
    }
}
