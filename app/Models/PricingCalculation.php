<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PricingCalculation extends Model
{
    protected $fillable = [
        'user_id',
        'operation_type',
        'name',
        'purchase_price',
        'freight',
        'input_taxes',
        'packaging',
        'other_direct_costs',
        'transport',
        'accommodation',
        'specific_materials',
        'other_inputs',
        'tax_rate',
        'desired_margin',
        'notes',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'freight' => 'decimal:2',
        'input_taxes' => 'decimal:2',
        'packaging' => 'decimal:2',
        'other_direct_costs' => 'decimal:2',
        'transport' => 'decimal:2',
        'accommodation' => 'decimal:2',
        'specific_materials' => 'decimal:2',
        'other_inputs' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'desired_margin' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function serviceHours(): HasMany
    {
        return $this->hasMany(PricingServiceHour::class);
    }
}
