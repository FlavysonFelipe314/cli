<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeCostProfile extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'employee_name',
        'position',
        'cost_center',
        'gross_salary',
        'monthly_hours',
        'transport_allowance',
        'meal_allowance',
        'health_insurance',
        'other_benefits',
        'inss_rate',
        'fgts_rate',
        'thirteenth_provision',
        'vacation_provision',
        'severance_provision',
        'other_charges',
        'equipment_tools',
        'training',
        'epi',
    ];

    protected $casts = [
        'gross_salary' => 'decimal:2',
        'transport_allowance' => 'decimal:2',
        'meal_allowance' => 'decimal:2',
        'health_insurance' => 'decimal:2',
        'other_benefits' => 'decimal:2',
        'inss_rate' => 'decimal:2',
        'fgts_rate' => 'decimal:2',
        'thirteenth_provision' => 'decimal:2',
        'vacation_provision' => 'decimal:2',
        'severance_provision' => 'decimal:2',
        'other_charges' => 'decimal:2',
        'equipment_tools' => 'decimal:2',
        'training' => 'decimal:2',
        'epi' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
