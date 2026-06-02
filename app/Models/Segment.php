<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Segment extends Model
{
    protected $fillable = ['name', 'color', 'panel_type', 'specs', 'base_price', 'sort_order', 'is_active'];
    protected $casts = ['is_active' => 'boolean', 'specs' => 'array'];

    public function gates(): HasMany
    {
        return $this->hasMany(Gate::class);
    }
}
