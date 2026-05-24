<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function editTask(Task $task)
    {
        if (!$task->classInfo || $task->classInfo->user_id !== auth()->id()) {
            abort(403, 'Nav piekļuves tiesību šim uzdevumam.');
        }

        return view('teacher.edit-task', compact('task'));
    }

    public function updateTask(Request $request, Task $task)
    {
        if (!$task->classInfo || $task->classInfo->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date'  => 'nullable|date',
            'due_date'    => 'nullable|date|after_or_equal:start_date',
            'color'       => 'nullable|string|max:7',
            'status'      => 'nullable|in:pending,completed',
        ]);

        $data = $request->only([
            'name', 'description', 'due_date', 'start_date', 'color', 'status'
        ]);
        $data['title'] = $data['name'];

        $task->update($data);

        return redirect()->route('teacher.class.show', $task->class_info_id)
                         ->with('success', 'Uzdevums veiksmīgi atjaunināts!');
    }

    public function destroyTask(Task $task)
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
