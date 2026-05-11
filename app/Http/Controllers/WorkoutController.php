<?php

namespace App\Http\Controllers;

use App\Models\Workout;
use App\Models\Exercise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkoutController extends Controller
{
    public function index()
    {
        $workouts = Workout::where('user_id', Auth::id())
                            ->with('exercise')
                            ->orderBy('workout_date', 'desc')
                            ->get();
        return view('workouts.index', compact('workouts'));
    }

    public function create()
    {
        $exercises = Exercise::all();
        return view('workouts.create', compact('exercises'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'exercise_id' => 'required|exists:exercises,id',
            'reps' => 'required|integer|min:1',
            'weight' => 'required|numeric|min:0',
            'workout_date' => 'required|date',
        ]);

        Workout::create([
            'user_id' => Auth::id(),
            'exercise_id' => $request->exercise_id,
            'reps' => $request->reps,
            'weight' => $request->weight,
            'workout_date' => $request->workout_date,
        ]);

        return redirect()->route('workouts.index')->with('success', 'Entrenamiento registrado.');
    }

    public function edit(Workout $workout)
    {
        if ($workout->user_id !== Auth::id()) {
            abort(403);
        }
        $exercises = Exercise::all();
        return view('workouts.edit', compact('workout', 'exercises'));
    }

    public function update(Request $request, Workout $workout)
    {
        if ($workout->user_id !== Auth::id()) abort(403);

        $request->validate([
            'exercise_id' => 'required|exists:exercises,id',
            'reps' => 'required|integer|min:1',
            'weight' => 'required|numeric|min:0',
            'workout_date' => 'required|date',
        ]);

        $workout->update($request->all());
        return redirect()->route('workouts.index')->with('success', 'Registro actualizado.');
    }

    public function destroy(Workout $workout)
    {
        if ($workout->user_id !== Auth::id()) abort(403);
        $workout->delete();
        return redirect()->route('workouts.index')->with('success', 'Registro eliminado.');
    }
}