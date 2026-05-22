<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

abstract class Controller
{
     public function index()
    {
        if (Auth::user()->usertype === 'admin') {
            return redirect()->route('admin.index');
        }
        elseif (Auth::user()->usertype === 'user') {
            return redirect()->route('user.index');
        }
        elseif (Auth::user()->usertype === 'teacher') {
            return redirect()->route('teacher.index');
        }
        else{
            return redirect()->route('login');
        }

        // $topics = Topic::all(); 
        // return view('dashboard', compact('topics'));
    }
}
