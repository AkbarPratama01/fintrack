<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MKiosTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'transaction_type',
        'product_code',
        'phone_number',
        'pln_customer_id',
        'customer_id',
        'balance_deducted',
        'cash_received',
        'profit',
        'provider',
        'wallet_id',
        'notes',
        'status',
        'transaction_date',
    ];

    protected $casts = [
        'balance_deducted' => 'decimal:2',
        'cash_received' => 'decimal:2',
        'profit' => 'decimal:2',
        'transaction_date' => 'datetime',
    ];

    /**
     * Get the user that owns the transaction.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the wallet used for this transaction.
     */
    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    /**
     * Get the customer for this transaction.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Scope for completed transactions.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for pending transactions.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for failed transactions.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope for pulsa transactions.
     */
    public function scopePulsa($query)
    {
        return $query->where('transaction_type', 'pulsa');
    }

    /**
     * Scope for DANA transactions.
     */
    public function scopeDana($query)
    {
        return $query->where('transaction_type', 'dana');
    }

    /**
     * Scope for GoPay transactions.
     */
    public function scopeGopay($query)
    {
        return $query->where('transaction_type', 'gopay');
    }

    /**
     * Scope for Token Listrik transactions.
     */
    public function scopeTokenListrik($query)
    {
        return $query->where('transaction_type', 'token_listrik');
    }

    /**
     * Get formatted balance deducted.
     */
    public function getFormattedBalanceDeductedAttribute()
    {
        return 'Rp ' . number_format($this->balance_deducted, 0, ',', '.');
    }

    /**
     * Get formatted cash received.
     */
    public function getFormattedCashReceivedAttribute()
    {
        return 'Rp ' . number_format($this->cash_received, 0, ',', '.');
    }

    /**
     * Get formatted profit.
     */
    public function getFormattedProfitAttribute()
    {
        return 'Rp ' . number_format($this->profit, 0, ',', '.');
    }
}
