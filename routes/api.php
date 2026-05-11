<?php

use App\Http\Controllers\Api\GymController;
use Illuminate\Support\Facades\Route;

Route::get('/categories', [GymController::class, 'getCategories']);
Route::get('/exercises', [GymController::class, 'getExercises']);
Route::get('/exercises/{id}', [GymController::class, 'showExercise']);
Route::get('/workouts/user/{userId}', [GymController::class, 'getUserWorkouts']);
Route::get('/stats', [GymController::class, 'getStats']);