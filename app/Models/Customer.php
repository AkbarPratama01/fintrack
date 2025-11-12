<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'address',
        'city',
        'province',
        'postal_code',
        'customer_type',
        'company_name',
        'tax_id',
        'credit_limit',
        'outstanding_balance',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
        'outstanding_balance' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user that owns the customer.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the M-KIOS transactions for the customer.
     */
    public function mkiosTransactions()
    {
        return $this->hasMany(MKiosTransaction::class);
    }

    /**
     * Get formatted credit limit.
     */
    public function getFormattedCreditLimitAttribute(): string
    {
        return 'Rp ' . number_format($this->credit_limit, 0, ',', '.');
    }

    /**
     * Get formatted outstanding balance.
     */
    public function getFormattedOutstandingBalanceAttribute(): string
    {
        return 'Rp ' . number_format($this->outstanding_balance, 0, ',', '.');
    }

    /**
     * Get available credit.
     */
    public function getAvailableCreditAttribute(): float
    {
        return $this->credit_limit - $this->outstanding_balance;
    }

    /**
     * Get formatted available credit.
     */
    public function getFormattedAvailableCreditAttribute(): string
    {
        return 'Rp ' . number_format($this->available_credit, 0, ',', '.');
    }

    /**
     * Scope a query to only include active customers.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include inactive customers.
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Scope a query to filter by customer type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('customer_type', $type);
    }
}
