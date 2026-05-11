<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-900 leading-tight dark:text-gray-100">Progreso</h2>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('workouts.index') }}" class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">
                    Historial
                </a>
                <a href="{{ route('workouts.create') }}" class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-800 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white">
                    Nueva serie
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <p class="text-xs text-gray-500 dark:text-gray-400">Solo series en estado <strong class="font-medium text-gray-700 dark:text-gray-300">completado</strong> entran en el progreso.</p>
            <div>
            @if($perExercise->isEmpty())
                <div class="rounded-xl border border-dashed border-gray-200 bg-white p-12 text-center text-gray-600 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400">
                    Sin datos.
                </div>
            @else
                <div class="space-y-4">
                    @foreach($perExercise as $row)
                        @php
                            $ex = $row['exercise'];
                            $latest = $row['latest'];
                            $pct = $row['best_weight'] > 0 ? min(100, round(((float) $latest->weight / $row['best_weight']) * 100)) : 0;
                            $ls = $latest->effective_series_count;
                        @endphp
                        <article class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
                            <div class="px-5 py-4 border-b border-gray-50 flex flex-wrap items-start justify-between gap-3 dark:border-gray-800">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $ex->name }}</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $ex->category->name ?? '—' }} · {{ $row['total_sets'] }} series</p>
                                </div>
                                <div class="flex flex-wrap gap-4 text-sm">
                                    <div class="text-right">
                                        <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Mejor peso</p>
                                        <p class="font-semibold text-gray-900 tabular-nums dark:text-gray-100">{{ number_format($row['best_weight'], 2, ',', '.') }} kg</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Mejor volumen</p>
                                        <p class="font-semibold text-indigo-700 tabular-nums dark:text-indigo-400">{{ number_format($row['best_volume'], 0, ',', '.') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Último</p>
                                        <p class="font-medium text-gray-800 capitalize dark:text-gray-200">{{ $latest->workout_date->locale('es')->translatedFormat('j M Y') }}</p>
                                        <p class="text-gray-600 tabular-nums dark:text-gray-400">
                                            @if($ls > 1){{ $ls }}× @endif{{ $latest->reps }} × {{ number_format($latest->weight, 2, ',', '.') }} kg
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="px-5 py-3 bg-slate-50/50 dark:bg-slate-900/40">
                                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 mb-2 dark:text-gray-400">Último vs récord (peso) · {{ $pct }}%</p>
                                <svg class="w-full h-2.5 block" viewBox="0 0 100 4" preserveAspectRatio="none" aria-hidden="true">
                                    <rect width="100" height="4" rx="2" fill="#e2e8f0" />
                                    <rect width="{{ max(0, min(100, (int) $pct)) }}" height="4" rx="2" fill="#6366f1" />
                                </svg>
                            </div>
                            <div class="px-5 py-4">
                                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 mb-2 dark:text-gray-400">Reciente</p>
                                <ul class="flex flex-wrap gap-2">
                                    @foreach($row['recent'] as $w)
                                        <li class="inline-flex items-center rounded-lg bg-gray-100 px-2.5 py-1 text-xs text-gray-700 tabular-nums dark:bg-gray-800 dark:text-gray-300">
                                            <span class="capitalize text-gray-500 mr-1.5 dark:text-gray-400">{{ $w->workout_date->locale('es')->translatedFormat('j M') }}</span>
                                            @if($w->effective_series_count > 1)<span class="mr-1 font-semibold text-indigo-600">×{{ $w->effective_series_count }}</span>@endif
                                            {{ $w->reps }}×{{ number_format($w->weight, 1, ',', '.') }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
            </div>
        </div>
    </div>
</x-app-layout>
