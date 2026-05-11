<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExerciseController extends Controller
{
    public function index()
    {
        $exercises = Exercise::with('category')->get();
        return view('exercises.index', compact('exercises'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('exercises.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('exercises', 'public');
        }

        Exercise::create($data);
        return redirect()->route('exercises.index')->with('success', 'Ejercicio creado.');
    }

    public function edit(Exercise $exercise)
    {
        $categories = Category::all();
        return view('exercises.edit', compact('exercise', 'categories'));
    }

    public function update(Request $request, Exercise $exercise)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            if ($exercise->image) {
                Storage::disk('public')->delete($exercise->image);
            }
            $data['image'] = $request->file('image')->store('exercises', 'public');
        }

        $exercise->update($data);
        return redirect()->route('exercises.index')->with('success', 'Ejercicio actualizado.');
    }

    public function destroy(Exercise $exercise)
    {
        if ($exercise->image) {
            Storage::disk('public')->delete($exercise->image);
        }
        $exercise->delete();
        return redirect()->route('exercises.index')->with('success', 'Ejercicio eliminado.');
    }
}