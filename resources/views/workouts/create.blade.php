<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-gray-100">Nueva serie</h2>
            <a href="{{ route('workouts.progress') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-500 shrink-0 dark:text-indigo-400">Progreso</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg border border-gray-100 dark:bg-gray-900 dark:border-gray-800">
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
                            <x-input-label for="series_count" value="Series" />
                            <x-text-input id="series_count" class="block mt-1 w-full" type="number" name="series_count" :value="old('series_count', 1)" min="1" max="30" required />
                            <x-input-error :messages="$errors->get('series_count')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="reps" value="Reps" />
                            <x-text-input id="reps" class="block mt-1 w-full" type="number" name="reps" :value="old('reps')" min="1" required />
                            <x-input-error :messages="$errors->get('reps')" class="mt-2" />
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="weight" value="Peso (kg)" />
                            <x-text-input id="weight" class="block mt-1 w-full" type="number" name="weight" :value="old('weight')" step="0.01" min="0" required />
                            <x-input-error :messages="$errors->get('weight')" class="mt-2" />
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Estado al guardar</p>
                            <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">Por defecto queda <strong class="font-semibold">pendiente</strong>. Márcalo solo si ya terminaste esa serie.</p>
                            <label class="mt-2 inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                <input type="checkbox" name="mark_completed" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800" @checked(old('mark_completed')) />
                                Guardar como <span class="font-semibold text-emerald-700 dark:text-emerald-400">completado</span>
                            </label>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Si no marcas la casilla, podrás pasarla a completado cuando quieras desde el historial.</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <x-primary-button>Guardar</x-primary-button>
                        <a href="{{ route('workouts.index') }}" class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
