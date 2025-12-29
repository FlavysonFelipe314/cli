<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProlaboreCalculation extends Model
{
    protected $fillable = [
        'user_id',
        'monthly_revenue',
        'fixed_costs',
        'variable_costs_percentage',
        'current_prolabore',
        'profit_margin_percentage',
        'reinvestment_percentage',
        'reserve_percentage',
    ];

    protected $casts = [
        'monthly_revenue' => 'decimal:2',
        'fixed_costs' => 'decimal:2',
        'variable_costs_percentage' => 'decimal:2',
        'current_prolabore' => 'decimal:2',
        'profit_margin_percentage' => 'decimal:2',
        'reinvestment_percentage' => 'decimal:2',
        'reserve_percentage' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function partners(): HasMany
    {
        return $this->hasMany(ProlaborePartner::class);
    }
}
