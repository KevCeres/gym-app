<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Ejercicio') }}: {{ $exercise->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('exercises.update', $exercise) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nombre del Ejercicio:</label>
                        <input type="text" name="name" id="name" value="{{ $exercise->name }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>

                    <div class="mb-4">
                        <label for="category_id" class="block text-gray-700 text-sm font-bold mb-2">Categoría (Grupo Muscular):</label>
                        <select name="category_id" id="category_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $exercise->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Descripción / Instrucciones:</label>
                        <textarea name="description" id="description" rows="3" class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline">{{ $exercise->description }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Imagen del Ejercicio:</label>
                        
                        @if($exercise->image)
                            <div class="mb-2">
                                <p class="text-xs text-gray-500 mb-1">Imagen actual:</p>
                                <img src="{{ asset('storage/' . $exercise->image) }}" class="w-32 h-32 object-cover rounded border">
                            </div>
                        @endif

                        <input type="file" name="image" id="image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="text-xs text-gray-400 mt-1">Deja este campo vacío si no deseas cambiar la imagen.</p>
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('exercises.index') }}" class="text-gray-600 hover:underline mr-4">Cancelar</a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-6 rounded shadow-lg transition duration-200">
                            Actualizar Ejercicio
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>