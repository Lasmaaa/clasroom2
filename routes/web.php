<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CodeVerificationController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ClassNameController;
use App\Http\Controllers\ClassController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// ==================== PUBLIC ====================
Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('auth/login');
})->name('login');

Route::get('/register', function () {
    return view('auth/register');
})->name('register');


// ==================== AUTH ====================
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        $user = Auth::user();
        if ($user->usertype === 'admin') {
            return redirect()->route('admin.index');
        } elseif ($user->usertype === 'teacher') {
            return redirect()->route('teacher.index');
        } elseif ($user->usertype === 'user') {
            return redirect()->route('user.index');
        }
        abort(404);
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');

    Route::view('/classroom', 'classroom.index')->name('classroom.index');
    Route::view('/classes', 'classes.index')->name('classes.index');
    Route::view('/assignments', 'assignments.index')->name('assignments.index');
    Route::view('/videoclass', 'videoclass.index')->name('videoclass.index');
});


// ==================== TEACHER ====================
Route::middleware(['auth', 'can:access-teacher'])->group(function () {

    Route::get('/teacher/index', [TeacherController::class, 'index'])->name('teacher.index');

    // Create Class
    Route::get('/teacher/create-class', [TeacherController::class, 'createClass'])
         ->name('teacher.create-class');

    Route::post('/teacher/classes', [TeacherController::class, 'storeClass'])
         ->name('teacher.classes.store');

    // Single Class View
    Route::get('/teacher/class/{id}', [TeacherController::class, 'showClass'])
         ->name('teacher.class');

    // Create Task
    Route::get('/teacher/class/{id}/create-task', [TeacherController::class, 'createTask'])
         ->name('teacher.create-task');

});



// ==================== ADMIN ====================
Route::middleware(['auth', 'can:access-admin'])->group(function () {
    Route::get('/admin', fn() => view('admin.index'))->name('admin.index');
});


// ==================== USER ====================
Route::middleware(['auth', 'can:access-user'])->group(function () {
    Route::get('/user', fn() => view('user.index'))->name('user.index');
});

require __DIR__.'/auth.php';