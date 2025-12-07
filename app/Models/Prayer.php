<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Prayer extends Model
{
    protected $fillable = [
        'user_id',
        'prayer_name',
        'prayer_date',
        'status',
        'prayed_at',
        'is_jamaah',
        'notes',
    ];

    protected $casts = [
        'prayer_date' => 'date',
        'prayed_at' => 'datetime',
        'is_jamaah' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get prayer name in Indonesian
     */
    public function getPrayerNameIndonesian(): string
    {
        return match($this->prayer_name) {
            'fajr' => 'Subuh',
            'dhuhr' => 'Dzuhur',
            'asr' => 'Ashar',
            'maghrib' => 'Maghrib',
            'isha' => 'Isya',
            default => $this->prayer_name,
        };
    }

    /**
     * Get status badge color
     */
    public function getStatusColor(): string
    {
        return match($this->status) {
            'on_time' => 'green',
            'qadha' => 'yellow',
            'missed' => 'red',
            default => 'gray',
        };
    }

    /**
     * Get status label
     */
    public function getStatusLabel(): string
    {
        return match($this->status) {
            'on_time' => 'Tepat Waktu',
            'qadha' => 'Qadha',
            'missed' => 'Terlewat',
            default => $this->status,
        };
    }
}
