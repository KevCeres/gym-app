<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoutineTemplateController;
use App\Http\Controllers\UserController; // 1. IMPORTANTE: Agregamos el de Usuarios
use App\Http\Controllers\WorkoutController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', DashboardController::class)->middleware(['auth', 'verified'])->name('dashboard');

// RUTAS PROTEGIDAS PARA ADMIN
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('exercises', ExerciseController::class);
    Route::resource('users', UserController::class); // 2. Agregamos el recurso de usuarios
});

// Rutinas y progreso: solo rol atleta (user), no admin
Route::middleware(['auth', 'athlete'])->group(function () {
    Route::get('workouts/progreso', [WorkoutController::class, 'progress'])->name('workouts.progress');
    Route::patch('workouts/{workout}/completar', [WorkoutController::class, 'toggleCompleted'])->name('workouts.toggle-completed');
    Route::resource('routine-templates', RoutineTemplateController::class)->except(['show']);
    Route::resource('workouts', WorkoutController::class);
});

// RUTAS PARA CUALQUIER USUARIO LOGUEADO
Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
