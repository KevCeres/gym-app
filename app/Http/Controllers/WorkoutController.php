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
        $workouts = Workout::query()
            ->where('user_id', Auth::id())
            ->with(['exercise.category'])
            ->orderByDesc('workout_date')
            ->orderByDesc('id')
            ->get();

        $workoutsByDate = $workouts
            ->groupBy(fn (Workout $w) => $w->workout_date->format('Y-m-d'))
            ->map(function ($group) {
                $dayWorkouts = $group->sort(function (Workout $a, Workout $b) {
                    $cmp = strcmp($a->exercise->name, $b->exercise->name);
                    if ($cmp !== 0) {
                        return $cmp;
                    }

                    return $b->id <=> $a->id;
                })->values();

                $seriesTotal = (int) $dayWorkouts->sum(fn (Workout $w) => $w->effective_series_count);
                $seriesCompletadas = (int) $dayWorkouts->where('completed', true)->sum(fn (Workout $w) => $w->effective_series_count);

                return [
                    'workouts' => $dayWorkouts,
                    'day_series' => $seriesTotal,
                    'day_series_completadas' => $seriesCompletadas,
                ];
            });

        $completados = $workouts->where('completed', true);

        $summary = [
            'dias_distintos' => $workoutsByDate->count(),
            'series_totales' => (int) $completados->sum(fn (Workout $w) => $w->effective_series_count),
            'volumen_total_kg' => round($completados->sum(fn (Workout $w) => $w->lineVolume()), 1),
        ];

        return view('workouts.index', compact('workoutsByDate', 'summary'));
    }

    public function progress()
    {
        $logs = Workout::query()
            ->where('user_id', Auth::id())
            ->where('completed', true)
            ->with('exercise.category')
            ->orderBy('workout_date')
            ->orderBy('id')
            ->get();

        $perExercise = $logs->groupBy('exercise_id')->map(function ($series) {
            $exercise = $series->first()->exercise;
            $bestWeight = (float) $series->max('weight');
            $bestVolume = round((float) $series->max(fn (Workout $w) => $w->lineVolume()), 1);

            $sorted = $series->sort(function (Workout $a, Workout $b) {
                if ($a->workout_date->ne($b->workout_date)) {
                    return $b->workout_date <=> $a->workout_date;
                }

                return $b->id <=> $a->id;
            })->values();

            $latest = $sorted->first();
            $recent = $sorted->take(8);

            return [
                'exercise' => $exercise,
                'best_weight' => $bestWeight,
                'best_volume' => $bestVolume,
                'latest' => $latest,
                'recent' => $recent,
                'total_sets' => (int) $series->sum(fn (Workout $w) => $w->effective_series_count),
            ];
        })->sortBy(fn (array $row) => $row['exercise']->name)->values();

        return view('workouts.progress', compact('perExercise'));
    }

    public function create()
    {
        $exercises = Exercise::all();
        return view('workouts.create', compact('exercises'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'exercise_id' => 'required|exists:exercises,id',
            'reps' => 'required|integer|min:1',
            'weight' => 'required|numeric|min:0',
            'workout_date' => 'required|date',
            'series_count' => 'required|integer|min:1|max:30',
        ]);

        $count = (int) $validated['series_count'];

        Workout::create([
            'user_id' => Auth::id(),
            'exercise_id' => (int) $validated['exercise_id'],
            'reps' => $validated['reps'],
            'weight' => $validated['weight'],
            'workout_date' => $validated['workout_date'],
            'series_count' => $count,
            'set_number' => 1,
            'completed' => $request->boolean('mark_completed'),
        ]);

        $message = $count === 1 ? 'Guardado.' : "Guardado ({$count} series).";

        return redirect()->route('workouts.index')->with('success', $message);
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
        if ($workout->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'exercise_id' => 'required|exists:exercises,id',
            'reps' => 'required|integer|min:1',
            'weight' => 'required|numeric|min:0',
            'workout_date' => 'required|date',
            'series_count' => 'required|integer|min:1|max:30',
        ]);

        $workout->update([
            'exercise_id' => (int) $validated['exercise_id'],
            'reps' => $validated['reps'],
            'weight' => $validated['weight'],
            'workout_date' => $validated['workout_date'],
            'series_count' => (int) $validated['series_count'],
            'completed' => $request->boolean('mark_completed'),
        ]);

        return redirect()->route('workouts.index')->with('success', 'Actualizado.');
    }

    public function toggleCompleted(Workout $workout)
    {
        if ($workout->user_id !== Auth::id()) {
            abort(403);
        }

        $next = ! $workout->completed;
        $workout->update(['completed' => $next]);

        return redirect()
            ->route('workouts.index')
            ->with('success', $next ? 'Marcado como completado.' : 'Marcado como pendiente.');
    }

    public function destroy(Workout $workout)
    {
        if ($workout->user_id !== Auth::id()) {
            abort(403);
        }

        $workout->delete();

        return redirect()->route('workouts.index')->with('success', 'Eliminado.');
    }
}
