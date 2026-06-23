<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClinicalGuideline extends Model
{
    protected $fillable = [
        'condition',
        'max_daily_carbs',
        'min_daily_fiber',
        'max_daily_sodium',
        'recommendations',
    ];
}
