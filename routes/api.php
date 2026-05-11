<?php

use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\GymController;
use Illuminate\Support\Facades\Route;

Route::apiResource('categories', CategoryApiController::class)->names([
    'index' => 'api.categories.index',
    'store' => 'api.categories.store',
    'show' => 'api.categories.show',
    'update' => 'api.categories.update',
    'destroy' => 'api.categories.destroy',
]);

Route::get('/exercises', [GymController::class, 'getExercises']);
Route::get('/exercises/{id}', [GymController::class, 'showExercise']);
Route::get('/workouts/user/{userId}', [GymController::class, 'getUserWorkouts']);
Route::get('/stats', [GymController::class, 'getStats']);
