<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

class Nutrient extends Model
{
    protected $fillable = ['name', 'unit'];

    public function foods(): BelongsToMany
    {
        return $this->belongsToMany(Food::class)->withPivot('value')->withTimestamps();
    }
}
