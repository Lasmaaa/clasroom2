<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;

class AuthenticatedSessionController extends Controller
{
    /**
     * Redirect pēc veiksmīgas pieteikšanās
     */
    // app/Http/Controllers/Auth/AuthenticatedSessionController.php

public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();           // <--- ŠITAS bija pazudis!
    $request->session()->regenerate();

    $user = Auth::user();

    // Redirect atkarībā no usertype
    if ($user->usertype === 'teacher') {
        return redirect()->route('teacher.index');
    } elseif ($user->usertype === 'admin') {
        return redirect()->route('admin.index');
    } else {
        // default student/user
        return redirect()->route('student.index');   // vai 'user.index'
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