<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'type',
        'icon',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Parent category (for sub-categories).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Sub-categories under this category.
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order');
    }

    /**
     * Products belonging to this category.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class)->orderBy('sort_order');
    }

    /**
     * Scope for active categories.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for root categories (no parent).
     */
    public function scopeParentsOnly(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Display icon/emoji for UI rendering.
     */
    public function getIconDisplayAttribute(): string
    {
        return match ($this->icon) {
            'gamepad' => '🎮',
            'tv' => '🍿',
            'user-check' => '👑',
            'shield' => '⚔️',
            'shirt' => '👕',
            null, '' => '📁',
            default => $this->icon,
        };
    }

    /**
     * Formatted label for category type.
     */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'game' => 'Top-Up Game',
            'account' => 'Akun Premium / Streaming',
            'game_account' => 'Jual Beli Akun Game',
            'joki' => 'Jasa Joki Rank',
            'preorder' => 'Preorder PDH & Uniform',
            null, '' => 'Umum',
            default => ucfirst(str_replace('_', ' ', $this->type)),
        };
    }
}
