<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;


class Project extends Model
{
    protected $fillable = ['user_id', 'session_id', 'source_image', 'result_image', 'canvas_state'];

    protected $casts = ['canvas_state' => 'array'];

    protected $appends = ['source_image_url', 'result_image_url'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lead(): HasOne
    {
        return $this->hasOne(Lead::class);
    }

    public function getSourceImageUrlAttribute(): ?string
    {
        if (!$this->source_image) return null;
        return asset('storage/' . $this->source_image);
    }

    public function getResultImageUrlAttribute(): ?string
    {
        if (!$this->result_image) return null;
        return asset('storage/' . $this->result_image);
    }
}
