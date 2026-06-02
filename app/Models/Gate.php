<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gate extends Model
{
    protected $fillable = [
        'category_id', 'door_model_id', 'type', 'leaf_type',
        'name', 'image', 'description',
        'colors', 'is_active', 'sort_order',
    ];

    const TYPE_GATE   = 'gate';
    const TYPE_WICKET = 'wicket';

    const LEAF_SINGLE = 'single';
    const LEAF_DOUBLE = 'double';
    const LEAF_TRIPLE = 'triple';

    protected $casts = [
        'colors'    => 'array',
        'is_active' => 'boolean',
    ];

    protected $appends = ['image_url'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(GateCategory::class, 'category_id');
    }

    public function doorModel(): BelongsTo
    {
        return $this->belongsTo(DoorModel::class, 'door_model_id');
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}
