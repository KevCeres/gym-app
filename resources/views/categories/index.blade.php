<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Categorías</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <div class="flex justify-between mb-4">
                    <h3 class="text-lg font-bold">Lista de Grupos Musculares</h3>
                    <a href="{{ route('categories.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">
                        + Nueva Categoría
                    </a>
                </div>

                <table class="w-full border-collapse border border-gray-200">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border p-2">ID</th>
                            <th class="border p-2">Nombre</th>
                            <th class="border p-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                        <tr>
                            <td class="border p-2 text-center">{{ $category->id }}</td>
                            <td class="border p-2">{{ $category->name }}</td>
                            <td class="border p-2 text-center">
                                <a href="{{ route('categories.edit', $category) }}" class="text-blue-600 hover:underline">Editar</a>
                                |
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('¿Borrar categoría?')">Eliminar</button>
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