<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Nuevo Ejercicio</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <form action="{{ route('exercises.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="block font-bold">Nombre del ejercicio:</label>
                        <input type="text" name="name" class="w-full border-gray-300 rounded" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-bold">Categoría:</label>
                        <select name="category_id" class="w-full border-gray-300 rounded">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block font-bold">Descripción:</label>
                        <textarea name="description" class="w-full border-gray-300 rounded"></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block font-bold">Imagen:</label>
                        <input type="file" name="image" class="w-full">
                    </div>

                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>