<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoorHandle extends Model
{
    protected $fillable = ['name', 'image', 'price', 'sort_order', 'is_active'];
    protected $casts    = ['is_active' => 'boolean'];
    protected $appends  = ['image_url'];

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}
