<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Category extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'type',
        'icon',
        'color',
    ];

    /**
     * Get the user that owns the category.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all transactions for the category.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get all budgets for the category.
     */
    public function budgets(): HasMany
    {
        return $this->hasMany(Budget::class);
    }

    /**
     * Scope a query to only include income categories.
     */
    public function scopeIncome(Builder $query): Builder
    {
        return $query->where('type', 'income');
    }

    /**
     * Scope a query to only include expense categories.
     */
    public function scopeExpense(Builder $query): Builder
    {
        return $query->where('type', 'expense');
    }

    /**
     * Scope a query to only include default categories (system categories).
     */
    public function scopeDefault(Builder $query): Builder
    {
        return $query->whereNull('user_id');
    }

    /**
     * Scope a query to include user's custom categories.
     */
    public function scopeUserCustom(Builder $query, $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Check if category is income type.
     */
    public function isIncome(): bool
    {
        return $this->type === 'income';
    }

    /**
     * Check if category is expense type.
     */
    public function isExpense(): bool
    {
        return $this->type === 'expense';
    }
}