<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HabitLog extends Model
{
    use HasFactory;

    protected $table = 'habit_logs';

    protected $fillable = [
        'habit_id',
        'date',
        'status',
    ];

    public $timestamps = false; // karena cuma pakai created_at

    protected $casts = [
        'status' => 'boolean',
    ];

    // Relasi ke Habit
    public function habit()
    {
        return $this->belongsTo(Habit::class);
    }
}