<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Registrar nueva serie</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg border border-gray-100">
                <form action="{{ route('workouts.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <x-input-label for="exercise_id" value="Ejercicio" />
                            <select id="exercise_id" name="exercise_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" required>
                                @foreach($exercises as $exercise)
                                    <option value="{{ $exercise->id }}" @selected(old('exercise_id') == $exercise->id)>{{ $exercise->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('exercise_id')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="workout_date" value="Fecha" />
                            <x-text-input id="workout_date" class="block mt-1 w-full" type="date" name="workout_date" :value="old('workout_date', date('Y-m-d'))" required />
                            <x-input-error :messages="$errors->get('workout_date')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="reps" value="Repeticiones" />
                            <x-text-input id="reps" class="block mt-1 w-full" type="number" name="reps" :value="old('reps')" min="1" required />
                            <x-input-error :messages="$errors->get('reps')" class="mt-2" />
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="weight" value="Peso (kg)" />
                            <x-text-input id="weight" class="block mt-1 w-full" type="number" name="weight" :value="old('weight')" step="0.01" min="0" required />
                            <x-input-error :messages="$errors->get('weight')" class="mt-2" />
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <x-primary-button>Guardar registro</x-primary-button>
                        <a href="{{ route('workouts.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
