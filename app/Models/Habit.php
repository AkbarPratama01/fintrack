<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Habit extends Model
{
    use HasFactory;

    protected $table = 'habits';

    protected $fillable = [
        'user_id',
        'name',
        'category',
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function logs()
    {
        return $this->hasMany(HabitLog::class);
    }

    public function todayLog()
    {
        return $this->hasOne(HabitLog::class)->whereDate('date', today());
    }
}