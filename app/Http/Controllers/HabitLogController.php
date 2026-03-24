<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Habit;
use App\Models\HabitLog;
use Illuminate\Support\Facades\Auth;

class HabitLogController extends Controller
{
    // ✅ Toggle checklist harian
    public function toggle(Request $request, $habitId)
    {
        $date = $request->date ?? today();

        $habit = Habit::where('user_id', Auth::id())
                    ->findOrFail($habitId);

        $log = HabitLog::firstOrCreate(
            [
                'habit_id' => $habit->id,
                'date' => $date
            ],
            [
                'status' => 0
            ]
        );

        $log->status = !$log->status;
        $log->save();

        return back();
    }
}