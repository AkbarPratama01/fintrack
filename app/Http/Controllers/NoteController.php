<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notes = Note::where('user_id', Auth::id())
            ->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('notes.index', compact('notes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('notes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'is_pinned' => 'boolean',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['is_pinned'] = $request->has('is_pinned');

        Note::create($validated);

        return redirect()->route('notes.index')
            ->with('success', 'Note berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note)
    {
        if ($note->user_id !== Auth::id()) {
            abort(403);
        }

        return view('notes.show', compact('note'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Note $note)
    {
        if ($note->user_id !== Auth::id()) {
            abort(403);
        }

        return view('notes.edit', compact('note'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Note $note)
    {
        if ($note->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'is_pinned' => 'boolean',
        ]);

        $validated['is_pinned'] = $request->has('is_pinned');

        $note->update($validated);

        return redirect()->route('notes.index')
            ->with('success', 'Note berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
        if ($note->user_id !== Auth::id()) {
            abort(403);
        }

        $note->delete();

        return redirect()->route('notes.index')
            ->with('success', 'Note berhasil dihapus!');
    }

    /**
     * Toggle pin status of a note.
     */
    public function togglePin(Note $note)
    {
        if ($note->user_id !== Auth::id()) {
            abort(403);
        }

        $note->update(['is_pinned' => !$note->is_pinned]);

        return back()->with('success', 'Pin status berhasil diubah!');
    }
}
