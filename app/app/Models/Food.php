<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    protected $table = 'foods';

    protected $fillable = [
        'name',
        'category',
        'calories',
        'protein_g',
        'carbs_g',
        'fats_g',
        'fiber_g',
        'glycemic_index',
        'cost_per_serving',
        'is_low_sodium',
    ];

    public function nutrients(): BelongsToMany
    {
        return $this->belongsToMany(Nutrient::class)->withPivot('value')->withTimestamps();
    }

    public function prices(): HasMany
    {
        return $this->hasMany(FoodPrice::class);
    }
}
