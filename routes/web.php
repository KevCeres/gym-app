<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\WorkoutController;
use App\Http\Controllers\UserController; // 1. IMPORTANTE: Agregamos el de Usuarios
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// RUTAS PROTEGIDAS PARA ADMIN
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('exercises', ExerciseController::class);
    Route::resource('users', UserController::class); // 2. Agregamos el recurso de usuarios
});

// RUTAS PARA CUALQUIER USUARIO LOGUEADO
Route::middleware('auth')->group(function () {
    Route::resource('workouts', WorkoutController::class);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';