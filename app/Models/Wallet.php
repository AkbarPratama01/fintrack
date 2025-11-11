<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\MKiosTransaction;

class Wallet extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'balance',
        'type',
        'currency',
        'description',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
    ];

    /**
     * Get the user that owns the wallet.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all transactions for the wallet.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get all M-KIOS transactions for the wallet.
     */
    public function mkiosTransactions(): HasMany
    {
        return $this->hasMany(MKiosTransaction::class);
    }

    /**
     * Add balance to the wallet.
     */
    public function addBalance(float $amount): void
    {
        $this->increment('balance', $amount);
    }

    /**
     * Subtract balance from the wallet.
     */
    public function subtractBalance(float $amount): void
    {
        $this->decrement('balance', $amount);
    }

    /**
     * Get formatted balance.
     */
    public function getFormattedBalanceAttribute(): string
    {
        return 'Rp ' . number_format((float)$this->balance, 0, ',', '.');
    }
}
