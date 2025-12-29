<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'route_name',
        'icon',
        'price',
        'category',
        'active',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'active' => 'boolean',
    ];

    public function userModules(): HasMany
    {
        return $this->hasMany(UserModule::class);
    }
}
