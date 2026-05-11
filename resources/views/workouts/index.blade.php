<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-900 leading-tight dark:text-gray-100">Historial</h2>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('workouts.progress') }}" class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">
                    Progreso
                </a>
                <a href="{{ route('workouts.create') }}" class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-800 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white">
                    Nueva serie
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-200" role="status">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="rounded-xl border border-gray-100 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Días</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $summary['dias_distintos'] }}</p>
                    <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Con al menos un registro</p>
                </div>
                <div class="rounded-xl border border-gray-100 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Series completadas</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $summary['series_totales'] }}</p>
                    <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Solo series en estado completado</p>
                </div>
                <div class="rounded-xl border border-gray-100 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Volumen</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ number_format($summary['volumen_total_kg'], 0, ',', '.') }}</p>
                    <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Solo series en estado completado</p>
                </div>
            </div>

            @if($workoutsByDate->isEmpty())
                <div class="rounded-xl border border-dashed border-gray-200 bg-white p-12 text-center dark:border-gray-700 dark:bg-gray-900">
                    <p class="text-gray-600 dark:text-gray-400">Sin series.</p>
                    <a href="{{ route('workouts.create') }}" class="mt-4 inline-block text-sm font-semibold text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">Añadir</a>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($workoutsByDate as $dateStr => $day)
                        <section class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
                            <div class="border-b border-gray-100 bg-slate-50/80 px-5 py-3 flex flex-wrap items-baseline justify-between gap-2 dark:border-gray-800 dark:bg-slate-900/50">
                                <h3 class="text-base font-semibold text-gray-900 capitalize dark:text-gray-100">
                                    {{ \Illuminate\Support\Carbon::parse($dateStr)->locale('es')->translatedFormat('l j \d\e F \d\e Y') }}
                                </h3>
                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $day['workouts']->count() }} {{ $day['workouts']->count() === 1 ? 'entrada' : 'entradas' }} ·
                                    @if($day['day_series_completadas'] !== $day['day_series'])
                                        {{ $day['day_series_completadas'] }}/{{ $day['day_series'] }} series
                                    @else
                                        {{ $day['day_series'] }} series
                                    @endif
                                </span>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-100 text-sm dark:divide-gray-800">
                                    <thead>
                                        <tr class="text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                            <th class="px-5 py-3">Ejercicio</th>
                                            <th class="px-5 py-3">Categoría</th>
                                            <th class="px-5 py-3 text-center">Series</th>
                                            <th class="px-5 py-3 text-right">Reps</th>
                                            <th class="px-5 py-3 text-right">Peso</th>
                                            <th class="px-5 py-3 text-right">Volumen</th>
                                            <th class="px-5 py-3 text-center">Estado de la serie</th>
                                            <th class="px-5 py-3 text-right">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                                        @foreach($day['workouts'] as $workout)
                                            <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-800/50 @if(!$workout->completed) bg-amber-50/40 dark:bg-amber-950/20 @endif">
                                                <td class="px-5 py-3 font-medium text-gray-900 dark:text-gray-100">{{ $workout->exercise->name }}</td>
                                                <td class="px-5 py-3 text-gray-600 dark:text-gray-400">{{ $workout->exercise->category->name ?? '—' }}</td>
                                                <td class="px-5 py-3 text-center">
                                                    <span class="inline-flex min-w-[2rem] justify-center rounded-md bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-800 tabular-nums dark:bg-slate-800 dark:text-slate-200">{{ $workout->effective_series_count }}</span>
                                                </td>
                                                <td class="px-5 py-3 text-right tabular-nums text-gray-700 dark:text-gray-300">{{ $workout->reps }}</td>
                                                <td class="px-5 py-3 text-right tabular-nums text-gray-700 dark:text-gray-300">{{ number_format($workout->weight, 2, ',', '.') }} kg</td>
                                                <td class="px-5 py-3 text-right tabular-nums text-gray-500 dark:text-gray-400">{{ number_format($workout->lineVolume(), 0, ',', '.') }}</td>
                                                <td class="px-5 py-3 text-center">
                                                    <form action="{{ route('workouts.toggle-completed', $workout) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="inline-flex items-center justify-center rounded-md px-3 py-1 text-xs font-semibold ring-1 ring-inset transition @if($workout->completed) bg-emerald-50 text-emerald-800 ring-emerald-200 hover:bg-emerald-100 dark:bg-emerald-950/50 dark:text-emerald-200 dark:ring-emerald-800 dark:hover:bg-emerald-900/40 @else bg-amber-50 text-amber-900 ring-amber-200 hover:bg-amber-100 dark:bg-amber-950/40 dark:text-amber-100 dark:ring-amber-800 dark:hover:bg-amber-900/40 @endif" title="Clic para cambiar estado">
                                                            {{ $workout->completed ? 'Completado' : 'Pendiente' }}
                                                        </button>
                                                    </form>
                                                </td>
                                                <td class="px-5 py-3 text-right whitespace-nowrap">
                                                    <a href="{{ route('workouts.edit', $workout) }}" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">Editar</a>
                                                    <span class="text-gray-300 mx-1">|</span>
                                                    <form action="{{ route('workouts.destroy', $workout) }}" method="POST" class="inline" data-confirm="¿Eliminar?" onsubmit="return confirm(this.dataset.confirm)">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="font-medium text-red-600 hover:text-red-500">Eliminar</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </section>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
