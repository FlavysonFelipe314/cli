<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProlaborePartner extends Model
{
    protected $fillable = [
        'prolabore_calculation_id',
        'name',
        'distribution_percentage',
    ];

    protected $casts = [
        'distribution_percentage' => 'decimal:2',
    ];

    public function calculation(): BelongsTo
    {
        return $this->belongsTo(ProlaboreCalculation::class, 'prolabore_calculation_id');
    }
}
