<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassInfo;
use Illuminate\Support\Facades\Auth;

class ClassController extends Controller
{
    public function show(ClassInfo $class_info)
    {
        if ($class_info->user_id !== Auth::id()) {
            abort(403);
        }

        $class_info->load('tasks');

        return view('teacher.class', ['class' => $class_info]);
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'class_name' => 'required|string|max:255',
        'color'      => 'required|string|max:7',
    ]);

    ClassInfo::create([
        'user_id'    => Auth::id(),
        'class_name' => $validated['class_name'],
        'color'      => $validated['color'],
    ]);

    return redirect()->route('teacher.index')->with('success', 'Klase veiksmīgi izveidota!');
}
}
