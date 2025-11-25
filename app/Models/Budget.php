<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Budget extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'amount',
        'period',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user that owns the budget.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category associated with the budget.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get spending for this budget period.
     */
    public function getSpending(): float
    {
        $query = Transaction::where('user_id', $this->user_id)
            ->where('category_id', $this->category_id)
            ->where('type', 'expense');

        if ($this->period === 'monthly') {
            $query->whereMonth('date', Carbon::parse($this->start_date)->month)
                  ->whereYear('date', Carbon::parse($this->start_date)->year);
        } else {
            $query->whereYear('date', Carbon::parse($this->start_date)->year);
        }

        return (float) $query->sum('amount');
    }

    /**
     * Get percentage of budget used.
     */
    public function getPercentage(): float
    {
        if ($this->amount <= 0) {
            return 0;
        }
        
        return min(($this->getSpending() / (float)$this->amount) * 100, 100);
    }

    /**
     * Get remaining budget amount.
     */
    public function getRemaining(): float
    {
        return max((float)$this->amount - $this->getSpending(), 0);
    }

    /**
     * Check if budget is exceeded.
     */
    public function isExceeded(): bool
    {
        return $this->getSpending() > (float)$this->amount;
    }

    /**
     * Get budget status.
     */
    public function getStatus(): string
    {
        $percentage = $this->getPercentage();
        
        if ($percentage >= 100) {
            return 'exceeded';
        } elseif ($percentage >= 80) {
            return 'warning';
        } else {
            return 'safe';
        }
    }
}
