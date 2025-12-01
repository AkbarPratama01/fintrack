<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WishList extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'target_amount',
        'saved_amount',
        'target_date',
        'priority',
        'status',
        'image_url',
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'saved_amount' => 'decimal:2',
        'target_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getProgressPercentageAttribute(): float
    {
        if ($this->target_amount == 0) {
            return 0;
        }
        return min(($this->saved_amount / $this->target_amount) * 100, 100);
    }

    public function getRemainingAmountAttribute(): float
    {
        return max($this->target_amount - $this->saved_amount, 0);
    }
}
