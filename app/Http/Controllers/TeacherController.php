<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\ClassInfo;
use Illuminate\Support\Str;

class TeacherController extends Controller
{
    public function index()
    {
        $classes = ClassInfo::where('user_id', Auth::id())->latest()->get();
        return view('teacher.index', compact('classes'));
    }

    public function createClass()
    {
        return view('teacher.create-class');
    }

    public function storeClass(Request $request)
    {
        $request->validate([
            'class_name' => 'required|string|max:255',
        ]);

        $code = strtoupper(Str::random(6));

        ClassInfo::create([
            'user_id'    => Auth::id(),
            'class_name' => $request->class_name,
            'color'      => $request->color ?? '#3b82f6',
            'class_code' => $code,
        ]);

        return redirect()->route('teacher.index')
                         ->with('success', 'Klase veiksmīgi izveidota!');
                         
    }

    public function showClass($id)
{
    $class = ClassInfo::where('user_id', Auth::id())
                      ->with('tasks')
                      ->findOrFail($id);

    if (empty($class->class_code)) {
        $class->class_code = strtoupper(Str::random(6));
        $class->save();
    }

    return view('teacher.class', compact('class'));
}

    public function createTask($id)
    {
        $class = ClassInfo::where('user_id', Auth::id())->findOrFail($id);
        return view('teacher.create-task', compact('class'));
    }
    public function storeTask(Request $request, $id)
{
    $class = ClassInfo::where('user_id', Auth::id())->findOrFail($id);

    $request->validate([
        'start_date'  => 'nullable|date',
        'due_date'    => 'nullable|date|after_or_equal:start_date',
        'task_name'        => 'required|string|max:255',
        'task_description' => 'nullable|string',
        'color'            => 'nullable|string|size:7',
    ]);

    $class->tasks()->create([
        'user_id'     => Auth::id(),
        'title'       => $request->task_name,
        'name'        => $request->task_name,
        'description' => $request->task_description,
        'color'       => $request->color ?? '#3b82f6',
        'start_date'  => $request->start_date,   
        'due_date'    => $request->due_date,    
    ]);

    return redirect()->route('teacher.class', $id)
                     ->with('success', 'Uzdevums veiksmīgi izveidots!');
}
}
