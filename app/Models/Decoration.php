<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Decoration extends Model
{
    protected $fillable = ['name', 'image', 'type', 'sort_order', 'is_active'];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) return null;
        return asset('storage/' . $this->image);
    }
}
