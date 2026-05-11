<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CategoryApiController extends Controller
{
    /**
     * Si abres /api/categories en el navegador (Accept: HTML), te lleva al CRUD web con botones.
     * Postman/código con Accept: application/json sigue recibiendo JSON.
     */
    public function index(Request $request): JsonResponse|RedirectResponse
    {
        $accept = (string) $request->header('Accept', '');
        $asksHtml = str_contains($accept, 'text/html') && ! str_contains($accept, 'application/json');
        if ($asksHtml) {
            return redirect()->route('categories.index');
        }

        return response()->json(Category::orderBy('name')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = Category::create($validated);

        return response()->json($category, 201);
    }

    public function show(Category $category): JsonResponse
    {
        return response()->json($category);
    }

    public function update(Request $request, Category $category): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update($validated);

        return response()->json($category);
    }

    public function destroy(Category $category): JsonResponse
    {
        $category->delete();

        return response()->json(['message' => 'Categoría eliminada'], 200);
    }
}
