<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::where('user_id', Auth::id())
                    ->latest()
                    ->get();

        return view('tasks.index', compact('tasks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
        ]);

        Task::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'priority' => $request->priority,
        ]);

        return back()->with('success', 'Task ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $task = Task::where('user_id', Auth::id())->findOrFail($id);

        $task->update($request->all());

        return back()->with('success', 'Task diupdate');
    }

    public function destroy($id)
    {
        $task = Task::where('user_id', Auth::id())->findOrFail($id);
        $task->delete();

        return back()->with('success', 'Task dihapus');
    }

    public function toggle($id)
    {
        $task = Task::where('user_id', Auth::id())->findOrFail($id);

        $task->status = $task->status === 'done' ? 'pending' : 'done';
        $task->save();

        return back();
    }
}