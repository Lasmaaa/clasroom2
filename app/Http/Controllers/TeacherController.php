<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ClassInfo;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;   // QR ģenerēšanai

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
        'color' => 'nullable|string',
    ]);

    $code = strtoupper(Str::random(6));

    $class = ClassInfo::create([
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
    $class = ClassInfo::where('user_id', Auth::id())->findOrFail($id);

    // Ja vecai klasei nav koda — ģenerējam automātiski
    if (empty($class->class_code)) {
        $class->class_code = strtoupper(Str::random(6));
        $class->save();
    }

    return view('teacher.class', compact('class'));
}
}