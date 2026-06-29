<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $fillable = [
        'plan_name',
        'description',
        'duration',
        'duration_type',
        'price',
        'status',
        'is_popular',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'status' => 'boolean',
        'is_popular' => 'boolean',
    ];
}
