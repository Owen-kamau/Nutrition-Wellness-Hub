<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class HealthProfile extends Model
{
    protected $fillable = [
        'user_id',
        'age',
        'gender',
        'weight_kg',
        'height_cm',
        'bmi',
        'medical_condition',
        'weekly_budget',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
