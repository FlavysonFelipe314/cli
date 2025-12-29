<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'asaas_subscription_id',
        'asaas_customer_id',
        'status',
        'starts_at',
        'ends_at',
        'next_billing_date',
        'cancelled_at',
        'cancellation_reason',
    ];

    protected $casts = [
        'starts_at' => 'date',
        'ends_at' => 'date',
        'next_billing_date' => 'date',
        'cancelled_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && 
               (!$this->ends_at || $this->ends_at->isFuture());
    }
}
