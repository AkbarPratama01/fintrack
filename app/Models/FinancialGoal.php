<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FinancialGoal extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'target_amount',
        'current_amount',
        'target_date',
        'category',
        'icon',
        'color',
        'status',
        'monthly_target',
        'auto_save',
        'notes',
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'current_amount' => 'decimal:2',
        'monthly_target' => 'decimal:2',
        'target_date' => 'date',
        'auto_save' => 'boolean',
    ];

    /**
     * Get the user that owns the goal.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the contributions for the goal.
     */
    public function contributions(): HasMany
    {
        return $this->hasMany(GoalContribution::class);
    }

    /**
     * Get formatted target amount.
     */
    public function getFormattedTargetAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->target_amount, 0, ',', '.');
    }

    /**
     * Get formatted current amount.
     */
    public function getFormattedCurrentAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->current_amount, 0, ',', '.');
    }

    /**
     * Get formatted remaining amount.
     */
    public function getFormattedRemainingAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->remaining_amount, 0, ',', '.');
    }

    /**
     * Get remaining amount.
     */
    public function getRemainingAmountAttribute(): float
    {
        return max(0, $this->target_amount - $this->current_amount);
    }

    /**
     * Get progress percentage.
     */
    public function getProgressPercentageAttribute(): float
    {
        if ($this->target_amount <= 0) {
            return 0;
        }
        return min(100, round(($this->current_amount / $this->target_amount) * 100, 2));
    }

    /**
     * Get days remaining.
     */
    public function getDaysRemainingAttribute(): int
    {
        return max(0, now()->diffInDays($this->target_date, false));
    }

    /**
     * Check if goal is completed.
     */
    public function getIsCompletedAttribute(): bool
    {
        return $this->current_amount >= $this->target_amount;
    }

    /**
     * Check if goal is overdue.
     */
    public function getIsOverdueAttribute(): bool
    {
        return $this->target_date < now() && !$this->is_completed;
    }

    /**
     * Get monthly required amount to reach goal.
     */
    public function getMonthlyRequiredAttribute(): float
    {
        $monthsRemaining = max(1, now()->diffInMonths($this->target_date));
        return $this->remaining_amount / $monthsRemaining;
    }

    /**
     * Scope a query to only include active goals.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include completed goals.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include goals by category.
     */
    public function scopeOfCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
