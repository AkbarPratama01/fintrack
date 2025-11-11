<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduledTransfer extends Model
{
    protected $fillable = [
        'user_id',
        'from_wallet_id',
        'to_wallet_id',
        'amount',
        'frequency',
        'start_date',
        'end_date',
        'next_execution_date',
        'status',
        'description',
        'execution_count',
        'last_executed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'next_execution_date' => 'datetime',
        'last_executed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function fromWallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'from_wallet_id');
    }

    public function toWallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'to_wallet_id');
    }

    public function calculateNextExecutionDate(): void
    {
        $date = $this->next_execution_date ? $this->next_execution_date->copy() : $this->start_date->copy();

        $this->next_execution_date = match($this->frequency) {
            'daily' => $date->addDay(),
            'weekly' => $date->addWeek(),
            'monthly' => $date->addMonth(),
            'yearly' => $date->addYear(),
        };
    }

    public function shouldExecute(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        if ($this->next_execution_date->gt(now())) {
            return false;
        }

        if ($this->end_date && $this->next_execution_date->gt($this->end_date)) {
            return false;
        }

        return true;
    }
}
