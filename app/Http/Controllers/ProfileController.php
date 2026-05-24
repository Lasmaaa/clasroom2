<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

   public function updateAvatar(Request $request)
{
    $request->validate([
        'avatar' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
    ]);

    /** @var \App\Models\User $user */
    $user = $request->user();

    // Dzēšam veco attēlu
    if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
        Storage::disk('public')->delete($user->avatar);
    }

    // Saglabājam jauno
    $path = $request->file('avatar')->store('avatars', 'public');

    // Saglabājam datubāzē
    $user->avatar = $path;
    $user->save();

    if (! $request->expectsJson() && ! $request->ajax()) {
        return Redirect::route('profile.edit')->with('status', 'avatar-updated');
    }

    return response()->json([
        'success' => true,
        'avatar_url' => asset('storage/' . $path),
        'path' => $path,           // palīdz debug
        'message' => 'Profila bilde veiksmīgi atjaunināta!'
    ]);
}
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
