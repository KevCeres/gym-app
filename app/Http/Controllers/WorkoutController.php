<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\RoutineTemplate;
use App\Models\Workout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use JsonException;

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

        $summary = [
            'ejercicios_distintos' => (int) $workouts->pluck('exercise_id')->unique()->count(),
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

        $exerciseTotalCount = $perExercise->count();

        return view('workouts.progress', [
            'perExercise' => $perExercise,
            'exerciseTotalCount' => $exerciseTotalCount,
        ]);
    }

    public function create(Request $request)
    {
        $exercises = Exercise::query()->with('category')->orderBy('name')->get();

        $exercisesForJs = $exercises->map(fn (Exercise $e) => [
            'id' => $e->id,
            'name' => $e->name,
            'category' => $e->category?->name,
            'image' => $e->image ? asset('storage/'.$e->image) : null,
            'haystack' => mb_strtolower($e->name.' '.($e->category?->name ?? '')),
        ])->values()->all();

        $templates = RoutineTemplate::query()
            ->where('user_id', Auth::id())
            ->with(['items' => fn ($q) => $q->orderBy('sort_order'), 'items.exercise.category'])
            ->orderBy('name')
            ->get();

        $templatesForJs = $templates->map(fn (RoutineTemplate $t) => [
            'id' => $t->id,
            'name' => $t->name,
            'items' => $t->items->map(fn ($i) => [
                'exercise_id' => $i->exercise_id,
                'default_reps' => $i->default_reps ?? 10,
                'default_series_count' => max(1, (int) ($i->default_series_count ?? 1)),
                'name' => $i->exercise->name,
                'category' => $i->exercise->category?->name,
                'image' => $i->exercise->image ? asset('storage/'.$i->exercise->image) : null,
            ]),
        ])->values()->all();

        $prefillTemplateId = null;
        if ($request->filled('plantilla')) {
            $tid = (int) $request->query('plantilla');
            if ($templates->contains('id', $tid)) {
                $prefillTemplateId = $tid;
            }
        }

        $defaultWorkoutDate = date('Y-m-d');

        try {
            $workoutCreatePayloadJson = json_encode([
                'exercises' => $exercisesForJs,
                'templates' => $templatesForJs,
                'prefillTemplateId' => $prefillTemplateId,
                'defaultDate' => $defaultWorkoutDate,
            ], JSON_THROW_ON_ERROR | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
        } catch (JsonException) {
            $workoutCreatePayloadJson = '{}';
        }

        return view('workouts.create', [
            'routineTemplates' => $templates,
            'defaultWorkoutDate' => $defaultWorkoutDate,
            'workoutCreatePayloadJson' => $workoutCreatePayloadJson,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'workout_date' => 'required|date',
            'mark_completed' => 'sometimes|boolean',
            'lines' => 'required|array|min:1|max:40',
            'lines.*.exercise_id' => 'required|exists:exercises,id',
            'lines.*.reps' => 'required|integer|min:1|max:999',
            'lines.*.weight' => 'required|numeric|min:0',
            'lines.*.series_count' => 'required|integer|min:1|max:30',
        ]);

        $completed = $request->boolean('mark_completed');

        DB::transaction(function () use ($validated, $completed) {
            foreach ($validated['lines'] as $line) {
                Workout::create([
                    'user_id' => Auth::id(),
                    'exercise_id' => (int) $line['exercise_id'],
                    'reps' => (int) $line['reps'],
                    'weight' => $line['weight'],
                    'workout_date' => $validated['workout_date'],
                    'series_count' => (int) $line['series_count'],
                    'set_number' => 1,
                    'completed' => $completed,
                ]);
            }
        });

        $n = count($validated['lines']);
        $message = $n === 1 ? 'Serie guardada.' : "Guardadas {$n} series.";

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
