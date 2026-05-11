@php
    $daysCount = $workoutsByDate->count();
    $entriesCount = $workoutsByDate->sum(fn ($d) => $d['workouts']->count());
    $seriesCount = $workoutsByDate->sum(fn ($d) => $d['day_series']);
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-900 leading-tight dark:text-gray-100">Historial</h2>
                <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">Todas tus series por día</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('workouts.progress') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">
                    Progreso
                </a>
                <a href="{{ route('workouts.create') }}" class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white">
                    Nueva serie
                </a>
            </div>
        </div>
    </x-slot>

    <div class="min-h-[50vh] bg-gradient-to-b from-slate-50 via-white to-indigo-50/30 py-8 dark:from-gray-950 dark:via-gray-900 dark:to-indigo-950/20 sm:py-10">
        <div class="mx-auto max-w-6xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="rounded-2xl border border-emerald-200/80 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-900 shadow-sm dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-200" role="status">
                    {{ session('success') }}
                </div>
            @endif

            @if($workoutsByDate->isNotEmpty())
                <div class="grid gap-4 sm:grid-cols-3">
                    <div class="sm:col-span-2 overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-md shadow-slate-200/40 ring-1 ring-slate-100 dark:border-slate-700 dark:bg-slate-900 dark:shadow-none dark:ring-slate-800">
                        <div class="h-1 bg-gradient-to-r from-indigo-500 via-violet-500 to-fuchsia-500" aria-hidden="true"></div>
                        <div class="p-5 sm:p-6">
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Ejercicios distintos</p>
                            <p class="mt-1 text-3xl font-bold tabular-nums tracking-tight text-slate-900 dark:text-white">{{ $summary['ejercicios_distintos'] }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-1 sm:gap-4">
                        <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm ring-1 ring-slate-100 dark:border-slate-700 dark:bg-slate-900 dark:ring-slate-800">
                            <p class="text-[10px] font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">Días</p>
                            <p class="mt-1 text-2xl font-bold tabular-nums text-slate-900 dark:text-white">{{ $daysCount }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm ring-1 ring-slate-100 dark:border-slate-700 dark:bg-slate-900 dark:ring-slate-800">
                            <p class="text-[10px] font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">Series</p>
                            <p class="mt-1 text-2xl font-bold tabular-nums text-indigo-600 dark:text-indigo-400">{{ $seriesCount }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if($workoutsByDate->isEmpty())
                <div class="rounded-2xl border border-dashed border-slate-200 bg-white/90 p-12 text-center shadow-sm dark:border-slate-700 dark:bg-slate-900/80">
                    <p class="text-slate-600 dark:text-slate-300">Aún no hay series en tu historial.</p>
                    <a href="{{ route('workouts.create') }}" class="mt-4 inline-flex items-center justify-center rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500">Registrar serie</a>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($workoutsByDate as $dateStr => $day)
                        <section class="overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-md shadow-slate-200/30 ring-1 ring-slate-100 dark:border-slate-700 dark:bg-slate-900 dark:shadow-none dark:ring-slate-800">
                            <div class="border-b border-slate-100 bg-gradient-to-r from-slate-50 to-indigo-50/40 px-5 py-4 dark:border-slate-800 dark:from-slate-900 dark:to-indigo-950/30 sm:px-6">
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                    <div>
                                        <h3 class="text-lg font-semibold capitalize tracking-tight text-slate-900 dark:text-white">
                                            {{ \Illuminate\Support\Carbon::parse($dateStr)->locale('es')->translatedFormat('l j \d\e F \d\e Y') }}
                                        </h3>
                                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                            {{ $day['workouts']->count() }} {{ $day['workouts']->count() === 1 ? 'entrada' : 'entradas' }}
                                            @if($day['day_series_completadas'] !== $day['day_series'])
                                                · {{ $day['day_series_completadas'] }}/{{ $day['day_series'] }} series completadas
                                            @else
                                                · {{ $day['day_series'] }} series
                                            @endif
                                        </p>
                                    </div>
                                    <span class="inline-flex w-fit items-center rounded-full bg-white/90 px-3 py-1 text-xs font-semibold text-indigo-800 shadow-sm ring-1 ring-indigo-100 dark:bg-slate-800 dark:text-indigo-200 dark:ring-indigo-900/50">
                                        {{ $day['workouts']->count() }} ejercicios este día
                                    </span>
                                </div>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-slate-100 text-sm dark:divide-slate-800">
                                    <thead>
                                        <tr class="bg-slate-50/90 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:bg-slate-800/50 dark:text-slate-400">
                                            <th scope="col" class="w-px whitespace-nowrap px-4 py-3.5 sm:px-5">Foto</th>
                                            <th scope="col" class="px-4 py-3.5 sm:px-5">Ejercicio</th>
                                            <th scope="col" class="hidden px-4 py-3.5 sm:table-cell sm:px-5">Categoría</th>
                                            <th scope="col" class="px-4 py-3.5 text-center sm:px-5">Series</th>
                                            <th scope="col" class="px-4 py-3.5 text-right sm:px-5">Reps</th>
                                            <th scope="col" class="px-4 py-3.5 text-right sm:px-5">Peso</th>
                                            <th scope="col" class="hidden px-4 py-3.5 text-right md:table-cell md:px-5">Volumen</th>
                                            <th scope="col" class="px-4 py-3.5 text-center sm:px-5">Estado</th>
                                            <th scope="col" class="px-4 py-3.5 text-right sm:px-5">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-50 dark:divide-slate-800/80">
                                        @foreach($day['workouts'] as $workout)
                                            <tr class="transition hover:bg-indigo-50/40 dark:hover:bg-slate-800/60 @if(!$workout->completed) bg-amber-50/50 dark:bg-amber-950/15 @endif">
                                                <td class="px-4 py-3 align-middle sm:px-5">
                                                    <x-exercise-thumbnail :exercise="$workout->exercise" class="ring-2 ring-white dark:ring-slate-800" />
                                                </td>
                                                <td class="px-4 py-3 sm:px-5">
                                                    <p class="font-semibold text-slate-900 dark:text-white">{{ $workout->exercise->name }}</p>
                                                    <p class="mt-0.5 text-xs text-slate-500 sm:hidden dark:text-slate-400">{{ $workout->exercise->category->name ?? '—' }}</p>
                                                </td>
                                                <td class="hidden px-4 py-3 text-slate-600 dark:text-slate-400 sm:table-cell sm:px-5">{{ $workout->exercise->category->name ?? '—' }}</td>
                                                <td class="px-4 py-3 text-center sm:px-5">
                                                    <span class="inline-flex min-w-[2.25rem] justify-center rounded-lg bg-indigo-100 px-2.5 py-1 text-xs font-bold tabular-nums text-indigo-900 dark:bg-indigo-950/60 dark:text-indigo-200">{{ $workout->effective_series_count }}</span>
                                                </td>
                                                <td class="px-4 py-3 text-right tabular-nums font-medium text-slate-700 dark:text-slate-300 sm:px-5">{{ $workout->reps }}</td>
                                                <td class="px-4 py-3 text-right tabular-nums text-slate-700 dark:text-slate-300 sm:px-5">{{ number_format($workout->weight, 2, ',', '.') }} <span class="text-xs font-normal text-slate-400">kg</span></td>
                                                <td class="hidden px-4 py-3 text-right tabular-nums text-slate-500 dark:text-slate-400 md:table-cell md:px-5">{{ number_format($workout->lineVolume(), 0, ',', '.') }}</td>
                                                <td class="px-4 py-3 text-center sm:px-5">
                                                    <form action="{{ route('workouts.toggle-completed', $workout) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="inline-flex items-center justify-center rounded-full px-3 py-1 text-xs font-semibold transition @if($workout->completed) bg-emerald-100 text-emerald-900 ring-1 ring-emerald-200/80 hover:bg-emerald-200/80 dark:bg-emerald-950/50 dark:text-emerald-200 dark:ring-emerald-800 dark:hover:bg-emerald-900/40 @else bg-amber-100 text-amber-950 ring-1 ring-amber-200/80 hover:bg-amber-200/70 dark:bg-amber-950/40 dark:text-amber-100 dark:ring-amber-800 dark:hover:bg-amber-900/40 @endif" title="Clic para cambiar estado">
                                                            {{ $workout->completed ? 'Completado' : 'Pendiente' }}
                                                        </button>
                                                    </form>
                                                </td>
                                                <td class="px-4 py-3 text-right sm:px-5">
                                                    <div class="flex flex-wrap items-center justify-end gap-x-2 gap-y-1">
                                                        <a href="{{ route('workouts.edit', $workout) }}" class="inline-flex rounded-lg px-2 py-1 text-xs font-semibold text-indigo-600 transition hover:bg-indigo-50 dark:text-indigo-400 dark:hover:bg-indigo-950/40">Editar</a>
                                                        <form action="{{ route('workouts.destroy', $workout) }}" method="POST" class="inline" data-confirm="¿Eliminar esta serie?" onsubmit="return confirm(this.dataset.confirm)">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="inline-flex rounded-lg px-2 py-1 text-xs font-semibold text-red-600 transition hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-950/30">Eliminar</button>
                                                        </form>
                                                    </div>
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
