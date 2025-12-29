<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IndirectCostAllocation extends Model
{
    protected $fillable = [
        'user_id',
        'reference_month',
        'reference_year',
        'allocation_mode',
        'allocation_base',
        'total_indirect_costs',
        'total_revenue',
        'allocation_percentage',
        'settings',
    ];

    protected $casts = [
        'total_indirect_costs' => 'decimal:2',
        'total_revenue' => 'decimal:2',
        'allocation_percentage' => 'decimal:2',
        'settings' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
