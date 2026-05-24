<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Redirect pēc veiksmīgas pieteikšanās
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);


        $request->session()->regenerate();

        $user = Auth::user();

        // Redirect atkarībā no usertype
        if ($user->usertype === 'teacher') {
            return redirect()->route('teacher.index');
        } 
        elseif ($user->usertype === 'admin') {
            return redirect()->route('admin.index');
        } 
        else {
            // default - student/user
            return redirect()->route('user.index');
        }
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
    public function create()
    {
        return view('auth.login');
    }

}