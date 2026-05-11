<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Registro de Entrenamiento') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('workouts.update', $workout) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="mb-4">
                            <label for="exercise_id" class="block text-gray-700 text-sm font-bold mb-2">Ejercicio:</label>
                            <select name="exercise_id" id="exercise_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline">
                                @foreach($exercises as $exercise)
                                    <option value="{{ $exercise->id }}" {{ $workout->exercise_id == $exercise->id ? 'selected' : '' }}>
                                        {{ $exercise->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="workout_date" class="block text-gray-700 text-sm font-bold mb-2">Fecha:</label>
                            <input type="date" name="workout_date" id="workout_date" value="{{ $workout->workout_date }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline" required>
                        </div>

                        <div class="mb-4">
                            <label for="reps" class="block text-gray-700 text-sm font-bold mb-2">Repeticiones:</label>
                            <input type="number" name="reps" id="reps" value="{{ $workout->reps }}" min="1" class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline" required>
                        </div>

                        <div class="mb-4">
                            <label for="weight" class="block text-gray-700 text-sm font-bold mb-2">Peso (kg):</label>
                            <input type="number" step="0.01" name="weight" id="weight" value="{{ $workout->weight }}" min="0" class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline" required>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('workouts.index') }}" class="text-gray-600 hover:underline mr-4">Cancelar</a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-6 rounded shadow transition duration-200">
                            Actualizar Registro
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>