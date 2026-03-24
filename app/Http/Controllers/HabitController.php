<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Habit;
use Illuminate\Support\Facades\Auth;

class HabitController extends Controller
{
    // 📋 List habit
    public function index(Request $request)
    {
        $month = $request->month 
            ? \Carbon\Carbon::parse($request->month) 
            : now();

        $start = $month->copy()->startOfMonth();
        $end = $month->copy()->endOfMonth();

        $habits = Habit::with(['logs' => function ($q) use ($start, $end) {
            $q->whereBetween('date', [$start, $end]);
        }])
        ->where('user_id', auth()->id())
        ->get();

        return view('habits.index', compact('habits', 'month'));
    }

    // ➕ Tambah habit
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100',
            'category' => 'nullable'
        ]);

        Habit::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'category' => $request->category,
            'frequency' => $request->frequency ?? 'daily',
            'days' => $request->days
        ]);

        return redirect()
            ->route('habits.index')
            ->with('success', 'Habit berhasil ditambahkan');
    }

    // 🔍 Detail (opsional, bisa dihapus kalau tidak dipakai)
    public function show($id)
    {
        $habit = Habit::where('user_id', Auth::id())
                      ->findOrFail($id);

        return view('habits.show', compact('habit')); // optional
    }

    // ✏️ Update habit
    public function update(Request $request, $id)
    {
        $habit = Habit::where('user_id', Auth::id())
                      ->findOrFail($id);

        $request->validate([
            'name' => 'required|max:100',
            'category' => 'nullable|max:50',
        ]);

        $habit->update([
            'name' => $request->name,
            'category' => $request->category,
            'frequency' => $request->frequency ?? 'daily',
            'days' => $request->days
        ]);

        return redirect()
            ->route('habits.index')
            ->with('success', 'Habit berhasil diupdate');
    }

    // ❌ Hapus habit
    public function destroy($id)
    {
        $habit = Habit::where('user_id', Auth::id())
                      ->findOrFail($id);

        $habit->delete();

        return redirect()
            ->route('habits.index')
            ->with('success', 'Habit berhasil dihapus');
    }
}