<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ClassInfo;

class ClassNameController extends Controller
{
    public function index()
    {
        $classItem = ClassInfo::where('user_id', Auth::id())->get();

        return view('teacher.class', compact('classItem'));
    }

    public function show($id)
    {
        $classItem = ClassInfo::findOrFail($id);

        return view('teacher.class', compact('classItem'));
    }
}

