<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\RoutineTemplate;
use App\Models\RoutineTemplateItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RoutineTemplateController extends Controller
{
    public function index(): View
    {
        $templates = RoutineTemplate::query()
            ->where('user_id', Auth::id())
            ->withCount('items')
            ->orderBy('name')
            ->get();

        return view('routine-templates.index', compact('templates'));
    }

    public function create(): View
    {
        $exercises = Exercise::query()->with('category')->orderBy('name')->get();

        return view('routine-templates.create', compact('exercises'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'items' => 'required|array|min:1|max:40',
            'items.*.exercise_id' => 'required|exists:exercises,id',
            'items.*.default_reps' => 'required|integer|min:1|max:999',
            'items.*.default_series_count' => 'required|integer|min:1|max:30',
        ]);

        DB::transaction(function () use ($validated) {
            $template = RoutineTemplate::create([
                'user_id' => Auth::id(),
                'name' => $validated['name'],
            ]);

            foreach ($validated['items'] as $index => $row) {
                RoutineTemplateItem::create([
                    'routine_template_id' => $template->id,
                    'exercise_id' => (int) $row['exercise_id'],
                    'sort_order' => $index,
                    'default_reps' => (int) $row['default_reps'],
                    'default_series_count' => (int) $row['default_series_count'],
                ]);
            }
        });

        return redirect()->route('routine-templates.index')->with('success', 'Plantilla creada.');
    }

    public function edit(RoutineTemplate $routineTemplate): View
    {
        $this->authorizeTemplate($routineTemplate);

        $routineTemplate->load(['items' => fn ($q) => $q->orderBy('sort_order'), 'items.exercise']);
        $exercises = Exercise::query()->with('category')->orderBy('name')->get();

        return view('routine-templates.edit', compact('routineTemplate', 'exercises'));
    }

    public function update(Request $request, RoutineTemplate $routineTemplate): RedirectResponse
    {
        $this->authorizeTemplate($routineTemplate);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'items' => 'required|array|min:1|max:40',
            'items.*.exercise_id' => 'required|exists:exercises,id',
            'items.*.default_reps' => 'required|integer|min:1|max:999',
            'items.*.default_series_count' => 'required|integer|min:1|max:30',
        ]);

        DB::transaction(function () use ($validated, $routineTemplate) {
            $routineTemplate->update(['name' => $validated['name']]);
            $routineTemplate->items()->delete();

            foreach ($validated['items'] as $index => $row) {
                RoutineTemplateItem::create([
                    'routine_template_id' => $routineTemplate->id,
                    'exercise_id' => (int) $row['exercise_id'],
                    'sort_order' => $index,
                    'default_reps' => (int) $row['default_reps'],
                    'default_series_count' => (int) $row['default_series_count'],
                ]);
            }
        });

        return redirect()->route('routine-templates.index')->with('success', 'Plantilla actualizada.');
    }

    public function destroy(RoutineTemplate $routineTemplate): RedirectResponse
    {
        $this->authorizeTemplate($routineTemplate);
        $routineTemplate->delete();

        return redirect()->route('routine-templates.index')->with('success', 'Plantilla eliminada.');
    }

    private function authorizeTemplate(RoutineTemplate $routineTemplate): void
    {
        abort_unless($routineTemplate->user_id === Auth::id(), 403);
    }
}
