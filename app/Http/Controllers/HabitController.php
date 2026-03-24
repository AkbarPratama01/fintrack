<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Habit;
use Illuminate\Support\Facades\Auth;

class HabitController extends Controller
{
    // 📋 List habit
    public function index()
    {
        $habits = Habit::where('user_id', Auth::id())->latest()->get();

        return view('habits.index', compact('habits'));
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
            'category' => $request->category
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