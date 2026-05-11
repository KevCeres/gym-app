<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Registrar Nueva Serie</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <form action="{{ route('workouts.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label class="block font-bold">Ejercicio:</label>
                            <select name="exercise_id" class="w-full border-gray-300 rounded" required>
                                @foreach($exercises as $exercise)
                                    <option value="{{ $exercise->id }}">{{ $exercise->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block font-bold">Fecha:</label>
                            <input type="date" name="workout_date" value="{{ date('Y-m-d') }}" class="w-full border-gray-300 rounded" required>
                        </div>
                        <div class="mb-4">
                            <label class="block font-bold">Repeticiones:</label>
                            <input type="number" name="reps" class="w-full border-gray-300 rounded" required>
                        </div>
                        <div class="mb-4">
                            <label class="block font-bold">Peso (kg):</label>
                            <input type="number" step="0.01" name="weight" class="w-full border-gray-300 rounded" required>
                        </div>
                    </div>
                    <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded font-bold">Guardar Registro</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>