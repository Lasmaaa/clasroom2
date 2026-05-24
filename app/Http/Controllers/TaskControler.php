<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function edit(Task $task)
    {
        // Droša pārbaude
        if (auth()->id() !== $task->teacher_id && auth()->id() !== optional($task->teacher)->id) {
            abort(403, 'Nav piekļuves tiesību šim uzdevumam.');
        }

        return view('teacher.edit-task', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        if (auth()->id() !== $task->teacher_id && auth()->id() !== optional($task->teacher)->id) {
            abort(403);
        }

        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'deadline'    => 'required|date',
            'points'      => 'nullable|integer|min:0',
        ]);

        $task->update([
            'title'       => $request->title,
            'description' => $request->description,
            'deadline'    => $request->deadline,
            'points'      => $request->points,
        ]);

        // Droši iegūstam class id
        $classId = $task->class_id ?? $task->classroom_id ?? $task->classroom?->id ?? null;

        return redirect()->route('teacher.class.show', $classId)
                         ->with('success', 'Uzdevums veiksmīgi atjaunināts!');
    }

    public function destroy(Task $task)
    {
        if (auth()->id() !== $task->teacher_id && auth()->id() !== optional($task->teacher)->id) {
            abort(403);
        }

        $classId = $task->class_id ?? $task->classroom_id ?? $task->classroom?->id ?? null;

        $task->delete();

        return redirect()->route('teacher.class.show', $classId)
                         ->with('success', 'Uzdevums veiksmīgi izdzēsts!');
    }
}