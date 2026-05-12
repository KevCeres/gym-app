<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Exercise;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExerciseApiController extends Controller
{
    public function index(): JsonResponse
    {
        $exercises = Exercise::query()
            ->with('category')
            ->orderBy('name')
            ->get();

        return response()->json($exercises);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('exercises', 'public');
        }

        $exercise = Exercise::create($validated)->load('category');

        return response()->json($exercise, 201);
    }

    public function show(Exercise $exercise): JsonResponse
    {
        return response()->json($exercise->load('category'));
    }

    public function update(Request $request, Exercise $exercise): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($exercise->image) {
                Storage::disk('public')->delete($exercise->image);
            }

            $validated['image'] = $request->file('image')->store('exercises', 'public');
        }

        $exercise->update($validated);

        return response()->json($exercise->load('category'));
    }

    public function destroy(Exercise $exercise): JsonResponse
    {
        if ($exercise->image) {
            Storage::disk('public')->delete($exercise->image);
        }

        $exercise->delete();

        return response()->json(['message' => 'Ejercicio eliminado'], 200);
    }
}
