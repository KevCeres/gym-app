<?php

use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\ExerciseApiController;
use App\Http\Controllers\Api\GymController;
use Illuminate\Support\Facades\Route;

Route::apiResource('categories', CategoryApiController::class)->names([
    'index' => 'api.categories.index',
    'store' => 'api.categories.store',
    'show' => 'api.categories.show',
    'update' => 'api.categories.update',
    'destroy' => 'api.categories.destroy',
]);

Route::get('/workouts/user/{userId}', [GymController::class, 'getUserWorkouts']);
Route::apiResource('exercises', ExerciseApiController::class)->names([
    'index' => 'api.exercises.index',
    'store' => 'api.exercises.store',
    'show' => 'api.exercises.show',
    'update' => 'api.exercises.update',
    'destroy' => 'api.exercises.destroy',
]);
Route::get('/stats', [GymController::class, 'getStats']);
