<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function edit(Task $task)
    {
        // Drošāka pārbaude
        if (!$task->classInfo || $task->classInfo->user_id !== auth()->id()) {
            abort(403, 'Nav piekļuves tiesību šim uzdevumam.');
        }

        return view('teacher.edit-task', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        if (!$task->classInfo || $task->classInfo->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date'    => 'required|date',
            'start_date'  => 'nullable|date',
            'color'       => 'nullable|string|max:7',
            'status'      => 'nullable|in:pending,completed',
        ]);

        $task->update($request->only([
            'name', 'description', 'due_date', 'start_date', 'color', 'status'
        ]));

        return redirect()->route('teacher.class.show', $task->class_info_id)
                         ->with('success', 'Uzdevums veiksmīgi atjaunināts!');
    }

    public function destroy(Task $task)
    {
        if (!$task->classInfo || $task->classInfo->user_id !== auth()->id()) {
            abort(403);
        }

        $classInfoId = $task->class_info_id;
        $task->delete();

        return redirect()->route('teacher.class.show', $classInfoId)
                         ->with('success', 'Uzdevums veiksmīgi izdzēsts!');
    }
}