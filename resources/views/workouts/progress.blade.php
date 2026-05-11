<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-900 leading-tight dark:text-gray-100">Progreso</h2>
                <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">Resumen por ejercicio (solo series completadas)</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('workouts.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">
                    Historial
                </a>
                <a href="{{ route('workouts.create') }}" class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white">
                    Nueva serie
                </a>
            </div>
        </div>
    </x-slot>

    <div class="min-h-[50vh] bg-gradient-to-b from-slate-50 via-white to-indigo-50/30 py-8 dark:from-gray-950 dark:via-gray-900 dark:to-indigo-950/20 sm:py-10">
        <div class="mx-auto max-w-3xl space-y-6 px-4 sm:px-6 lg:px-8">
            {{-- Resumen compacto --}}
            <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-md shadow-slate-200/40 ring-1 ring-slate-100 dark:border-slate-700 dark:bg-slate-900 dark:shadow-none dark:ring-slate-800">
                <div class="h-1 bg-gradient-to-r from-indigo-500 via-violet-500 to-fuchsia-500" aria-hidden="true"></div>
                <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:justify-between sm:p-6">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Ejercicios con datos</p>
                        <p class="mt-1 text-3xl font-bold tabular-nums tracking-tight text-slate-900 dark:text-white">{{ $exerciseTotalCount }}</p>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Peso máximo, volumen y últimas series registradas</p>
                    </div>
                    @if($exerciseTotalCount > 0)
                        <span id="progress-match-badge" class="inline-flex w-fit items-center rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-800 dark:bg-indigo-950/50 dark:text-indigo-200" aria-live="polite">
                            {{ $exerciseTotalCount }} en lista
                        </span>
                    @endif
                </div>
            </div>

            {{-- Buscador (filtrado al escribir) --}}
            @if($exerciseTotalCount > 0)
                <div class="relative" role="search">
                    <label for="progress-search" class="sr-only">Buscar ejercicio</label>
                    <div class="pointer-events-none absolute inset-y-0 start-0 flex items-center ps-4">
                        <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </div>
                    <input
                        id="progress-search"
                        type="search"
                        name="q"
                        autocomplete="off"
                        placeholder="Buscar por nombre o categoría (resultados al instante)…"
                        class="w-full rounded-2xl border border-slate-200 bg-white py-3.5 ps-12 pe-28 text-sm text-slate-900 shadow-sm ring-indigo-500/20 placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 dark:border-slate-600 dark:bg-slate-900 dark:text-white dark:placeholder:text-slate-500"
                    >
                    <div class="absolute inset-y-1 end-1 flex items-center gap-1">
                        <button type="button" id="progress-search-clear" class="hidden items-center rounded-xl px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800" aria-hidden="true">
                            Limpiar
                        </button>
                    </div>
                </div>
            @endif

            @if($exerciseTotalCount === 0)
                <div class="rounded-2xl border border-dashed border-slate-200 bg-white/90 p-12 text-center shadow-sm dark:border-slate-700 dark:bg-slate-900/80">
                    <p class="text-slate-600 dark:text-slate-300">Aún no tienes series completadas en el historial.</p>
                    <a href="{{ route('workouts.create') }}" class="mt-4 inline-flex items-center justify-center rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500">Registrar serie</a>
                </div>
            @else
                <div id="progress-no-results" class="hidden rounded-2xl border border-amber-200 bg-amber-50/80 px-5 py-6 text-center dark:border-amber-900/40 dark:bg-amber-950/30" role="status">
                    <p class="text-sm font-medium text-amber-900 dark:text-amber-100">No hay ejercicios que coincidan con «<span id="progress-no-results-query"></span>».</p>
                    <button type="button" id="progress-no-results-clear" class="mt-3 text-sm font-semibold text-indigo-700 hover:text-indigo-600 dark:text-indigo-400">Ver todos</button>
                </div>
                <div id="progress-exercise-list" class="space-y-5">
                    @foreach($perExercise as $row)
                        @php
                            $ex = $row['exercise'];
                            $latest = $row['latest'];
                            $pct = $row['best_weight'] > 0 ? min(100, round(((float) $latest->weight / $row['best_weight']) * 100)) : 0;
                            $ls = $latest->effective_series_count;
                            $haystack = mb_strtolower($ex->name.' '.($ex->category->name ?? ''));
                        @endphp
                        <article data-progress-item data-search-haystack="{{ e($haystack) }}" class="group overflow-hidden rounded-2xl border border-slate-200/90 border-l-4 border-l-indigo-500 bg-white shadow-md shadow-slate-200/30 ring-1 ring-slate-100 transition hover:border-l-indigo-400 hover:shadow-lg dark:border-slate-700 dark:border-l-indigo-500 dark:bg-slate-900 dark:shadow-none dark:ring-slate-800 dark:hover:border-l-indigo-400">
                            <div>
                                <div class="border-b border-slate-100 bg-gradient-to-br from-white to-slate-50/80 px-5 py-5 dark:border-slate-800 dark:from-slate-900 dark:to-slate-900/80 sm:px-6">
                                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                        <div class="flex gap-4">
                                            <div class="shrink-0 pt-0.5">
                                                <x-exercise-thumbnail :exercise="$ex" large class="ring-2 ring-white dark:ring-slate-800" />
                                            </div>
                                            <div class="min-w-0">
                                                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">{{ $ex->name }}</h3>
                                                <p class="mt-1 inline-flex rounded-lg bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-600 dark:bg-slate-800 dark:text-slate-300">{{ $ex->category->name ?? 'Sin categoría' }}</p>
                                                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ $row['total_sets'] }} series completadas en total</p>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-3 gap-3 sm:shrink-0 sm:gap-4">
                                            <div class="rounded-xl border border-slate-100 bg-white px-3 py-2 text-center shadow-sm dark:border-slate-700 dark:bg-slate-800/50">
                                                <p class="text-[10px] font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">Mejor peso</p>
                                                <p class="mt-1 text-sm font-bold tabular-nums text-slate-900 dark:text-white">{{ number_format($row['best_weight'], 1, ',', '.') }} <span class="text-xs font-normal text-slate-500">kg</span></p>
                                            </div>
                                            <div class="rounded-xl border border-indigo-100 bg-indigo-50/60 px-3 py-2 text-center dark:border-indigo-900/40 dark:bg-indigo-950/30">
                                                <p class="text-[10px] font-semibold uppercase tracking-wide text-indigo-600 dark:text-indigo-400">Volumen</p>
                                                <p class="mt-1 text-sm font-bold tabular-nums text-indigo-900 dark:text-indigo-100">{{ number_format($row['best_volume'], 0, ',', '.') }}</p>
                                            </div>
                                            <div class="rounded-xl border border-slate-100 bg-white px-3 py-2 text-center shadow-sm dark:border-slate-700 dark:bg-slate-800/50">
                                                <p class="text-[10px] font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">Último</p>
                                                <p class="mt-1 text-xs font-semibold capitalize text-slate-800 dark:text-slate-200">{{ $latest->workout_date->locale('es')->translatedFormat('j M') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="border-b border-slate-100 bg-slate-50/90 px-5 py-4 dark:border-slate-800 dark:bg-slate-900/60 sm:px-6">
                                    <div class="flex flex-wrap items-end justify-between gap-2">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Último entreno vs récord de peso</p>
                                        <span class="text-sm font-bold tabular-nums text-indigo-600 dark:text-indigo-400">{{ (int) $pct }}%</span>
                                    </div>
                                    <div class="mt-2 h-2.5 overflow-hidden rounded-full bg-slate-200 dark:bg-slate-700">
                                        <div class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-violet-500 transition-all duration-500" style="width: {{ max(0, min(100, (int) $pct)) }}%"></div>
                                    </div>
                                    <p class="mt-2 text-xs text-slate-600 dark:text-slate-400">
                                        @if($ls > 1){{ $ls }}× @endif{{ $latest->reps }} reps · {{ number_format($latest->weight, 1, ',', '.') }} kg
                                    </p>
                                </div>

                                <div class="px-5 py-4 sm:px-6">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Series recientes</p>
                                    <ul class="mt-3 flex flex-wrap gap-2">
                                        @foreach($row['recent'] as $w)
                                            <li class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium tabular-nums text-slate-700 shadow-sm dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200">
                                                <span class="text-slate-400 dark:text-slate-500">{{ $w->workout_date->locale('es')->translatedFormat('j M') }}</span>
                                                @if($w->effective_series_count > 1)
                                                    <span class="font-semibold text-indigo-600 dark:text-indigo-400">×{{ $w->effective_series_count }}</span>
                                                @endif
                                                <span>{{ $w->reps }}×{{ number_format($w->weight, 1, ',', '.') }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    @if($exerciseTotalCount > 0)
        <script>
            (function () {
                var input = document.getElementById('progress-search');
                var list = document.getElementById('progress-exercise-list');
                if (!input || !list) return;

                var items = list.querySelectorAll('[data-progress-item]');
                var total = items.length;
                var badge = document.getElementById('progress-match-badge');
                var clearBtn = document.getElementById('progress-search-clear');
                var noResults = document.getElementById('progress-no-results');
                var noResultsQuery = document.getElementById('progress-no-results-query');
                var noResultsClear = document.getElementById('progress-no-results-clear');

                function filter() {
                    var q = input.value.trim().toLowerCase();
                    var visible = 0;

                    items.forEach(function (el) {
                        var hay = el.getAttribute('data-search-haystack') || '';
                        var show = q === '' || hay.indexOf(q) !== -1;
                        el.classList.toggle('hidden', !show);
                        if (show) visible++;
                    });

                    if (noResults) {
                        noResults.classList.toggle('hidden', !(q !== '' && visible === 0));
                        if (noResultsQuery) noResultsQuery.textContent = q;
                    }

                    if (badge) {
                        badge.textContent = q === '' ? total + ' en lista' : visible + ' coincidencia' + (visible === 1 ? '' : 's');
                    }

                    if (clearBtn) {
                        var hideClear = q === '';
                        clearBtn.classList.toggle('hidden', hideClear);
                        clearBtn.classList.toggle('inline-flex', !hideClear);
                        clearBtn.setAttribute('aria-hidden', hideClear ? 'true' : 'false');
                    }
                }

                function clearSearch() {
                    input.value = '';
                    filter();
                    input.focus();
                }

                input.addEventListener('input', filter);
                input.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape') {
                        clearSearch();
                    }
                });
                if (clearBtn) clearBtn.addEventListener('click', clearSearch);
                if (noResultsClear) noResultsClear.addEventListener('click', clearSearch);

                filter();
            })();
        </script>
    @endif
</x-app-layout>
