<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RunningText extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'icon',
        'url',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Scope query to only include active running texts.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Display icon with fallback.
     */
    public function getDisplayIconAttribute(): string
    {
        return $this->icon ?: '⚡';
    }

    /**
     * Complete formatted line with icon.
     */
    public function getFormattedTextAttribute(): string
    {
        return trim(($this->icon ? $this->icon.' ' : '').$this->content);
    }
}
