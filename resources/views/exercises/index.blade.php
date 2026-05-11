<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Ejercicios</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <div class="flex justify-between mb-4">
                    <h3 class="text-lg font-bold">Catálogo de Ejercicios</h3>
                    <a href="{{ route('exercises.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">+ Nuevo Ejercicio</a>
                </div>

                <table class="w-full border">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="p-2">Imagen</th>
                            <th class="p-2">Nombre</th>
                            <th class="p-2">Categoría</th>
                            <th class="p-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($exercises as $exercise)
                        <tr>
                            <td class="p-2 text-center">
                                @if($exercise->image)
                                    <img src="{{ asset('storage/' . $exercise->image) }}" class="w-16 h-16 object-cover mx-auto rounded">
                                @else
                                    <span class="text-gray-400 text-xs">Sin foto</span>
                                @endif
                            </td>
                            <td class="p-2">{{ $exercise->name }}</td>
                            <td class="p-2">{{ $exercise->category->name }}</td>
                            <td class="p-2 text-center">
                                <a href="{{ route('exercises.edit', $exercise) }}" class="text-blue-600">Editar</a> |
                                <form action="{{ route('exercises.destroy', $exercise) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button class="text-red-600">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>