<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Code;      
use App\Models\ClassInfo;
use Illuminate\Support\Str;

class CodeVerificationController extends Controller
{
    public function generate(Request $request)
    {
        $validated = $request->validate([
            'auth_method' => 'required|in:numeric,qr',
        ]);

        do {
            if ($validated['auth_method'] === 'numeric') {
                $generatedCode = (string) random_int(100000, 999999);
            } else {
                $generatedCode = Str::upper(Str::random(16));
            }

            $exists = Code::where('code', $generatedCode)->exists();
        } while ($exists);

        return redirect()->back()
            ->with('generated_code', $generatedCode)
            ->with('code_type', $validated['auth_method'])
            ->with('success', 'Kods ģenerēts!');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string',
            'type' => 'required|in:numeric,qr',
        ]);

        $verificationCode = Code::create([
            'user_id' => Auth::id(),
            'type'    => $validated['type'],
            'code'    => $validated['code'],
            'is_used' => false,
        ]);

        return redirect()->route('teacher.show', $verificationCode->code)
                         ->with('success', 'Kods veiksmīgi saglabāts!');
    }

    public function show($code)
    {
        // 1. Atrodam pašu kodu datubāzē
        $codeModel = Code::where('code', $code)
                        ->where('user_id', Auth::id())
                        ->first();

        if (!$codeModel) {
            abort(404, 'Kods nav atrasts vai Jums nav piekļuves tiesību.');
        }

        // 2. Atrodam PAŠU JAUNĀKO klasi, ko šis skolotājs tikko ierakstīja create-class lapā
        $class = ClassInfo::where('user_id', Auth::id())
                          ->latest()
                          ->first();

        // 3. Sagatavojam krāsu (ja klase nav atrasta, iestatām drošu noklusējuma zilo)
        $classColor = '#3b82f6'; 
        if ($class && $class->color && preg_match('/^#[a-fA-F0-9]{3,6}$/', $class->color)) {
            $classColor = $class->color;
        }

        // 4. Nosūtām visus datus uz skatu
        return view('teacher.show', compact('codeModel', 'class', 'classColor'));
    }
    }