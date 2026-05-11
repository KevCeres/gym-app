<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mi Historial de Entrenamiento</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <div class="flex justify-between mb-4">
                    <h3 class="text-lg font-bold">Mis Registros</h3>
                    <a href="{{ route('workouts.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">+ Registrar Serie</a>
                </div>

                <table class="w-full border">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="p-2 border">Fecha</th>
                            <th class="p-2 border">Ejercicio</th>
                            <th class="p-2 border">Reps</th>
                            <th class="p-2 border">Peso (kg)</th>
                            <th class="p-2 border">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($workouts as $workout)
                        <tr>
                            <td class="p-2 border text-center">{{ $workout->workout_date }}</td>
                            <td class="p-2 border">{{ $workout->exercise->name }}</td>
                            <td class="p-2 border text-center">{{ $workout->reps }}</td>
                            <td class="p-2 border text-center">{{ $workout->weight }} kg</td>
                            <td class="p-2 border text-center">
                                <a href="{{ route('workouts.edit', $workout) }}" class="text-blue-500">Editar</a> |
                                <form action="{{ route('workouts.destroy', $workout) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button class="text-red-500">Borrar</button>
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