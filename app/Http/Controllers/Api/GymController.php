<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Exercise;
use App\Models\Workout;
use App\Models\User;

class GymController extends Controller
{
    public function getExercises() {
        return response()->json(Exercise::with('category')->get(), 200);
    }

    public function showExercise($id) {
        $exercise = Exercise::find($id);
        if (!$exercise) return response()->json(['error' => 'Ejercicio no encontrado'], 404);
        return response()->json($exercise, 200);
    }
    public function getUserWorkouts($userId) {
        $workouts = Workout::where('user_id', $userId)->with('exercise')->get();
        return response()->json($workouts, 200);
    }

    public function getStats() {
        return response()->json([
            'total_usuarios' => User::count(),
            'total_ejercicios' => Exercise::count(),
            'total_rutinas_registradas' => Workout::count(),
        ], 200);
    }
}