<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CalendarEvent extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'event_date',
        'event_time',
        'event_type',
        'scope',
        'related_model_type',
        'related_model_id',
        'color',
    ];

    protected $casts = [
        'event_date' => 'date',
        'event_time' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function relatedModel(): MorphTo
    {
        return $this->morphTo('related_model');
    }
}
