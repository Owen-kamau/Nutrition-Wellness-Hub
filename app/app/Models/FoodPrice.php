<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class FoodPrice extends Model
{
    protected $fillable = ['food_id', 'price', 'effective_date'];

    public function food(): BelongsTo
    {
        return $this->belongsTo(Food::class);
    }
}
