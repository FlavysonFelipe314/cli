<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TeamMember extends Model
{
    protected $fillable = [
        'user_id',
        'member_user_id',
        'name',
        'email',
        'position',
        'employment_type',
        'entry_date',
        'estimated_monthly_cost',
        'status',
    ];

    protected $casts = [
        'entry_date' => 'date',
        'estimated_monthly_cost' => 'decimal:2',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function ownerId(): int
    {
        return $this->user_id;
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(User::class, 'member_user_id');
    }

    public function invitation(): HasOne
    {
        return $this->hasOne(TeamInvitation::class);
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'team_user', 'team_member_id', 'user_id')
            ->withPivot('role')
            ->withTimestamps();
    }
}
