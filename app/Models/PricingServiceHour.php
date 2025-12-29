<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PricingServiceHour extends Model
{
    protected $fillable = [
        'pricing_calculation_id',
        'function_name',
        'hourly_rate',
        'hours',
    ];

    protected $casts = [
        'hourly_rate' => 'decimal:2',
        'hours' => 'decimal:2',
    ];

    public function calculation(): BelongsTo
    {
        return $this->belongsTo(PricingCalculation::class, 'pricing_calculation_id');
    }
}
