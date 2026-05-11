<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-900 leading-tight dark:text-gray-100">Categorías</h2>
            <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">Grupos musculares y tipos de entreno</p>
        </div>
    </x-slot>

    <div class="min-h-[50vh] bg-gradient-to-b from-slate-50 via-white to-indigo-50/30 py-8 dark:from-gray-950 dark:via-gray-900 dark:to-indigo-950/20 sm:py-10">
        <div class="mx-auto max-w-5xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="rounded-2xl border border-emerald-200/80 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-900 shadow-sm dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-200" role="status">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-md shadow-slate-200/40 ring-1 ring-slate-100 dark:border-slate-700 dark:bg-slate-900 dark:shadow-none dark:ring-slate-800">
                <div class="h-1 bg-gradient-to-r from-indigo-500 via-violet-500 to-fuchsia-500" aria-hidden="true"></div>

                <div class="flex flex-col gap-4 border-b border-slate-100 p-5 dark:border-slate-800 sm:flex-row sm:items-center sm:justify-between sm:p-6">
                    <div class="relative max-w-md flex-1" role="search">
                        <label for="category-search" class="sr-only">Buscar categoría</label>
                        <div class="pointer-events-none absolute inset-y-0 start-0 flex items-center ps-3">
                            <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                        </div>
                        <input
                            id="category-search"
                            type="search"
                            autocomplete="off"
                            placeholder="Filtrar por nombre…"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50/80 py-2.5 ps-10 pe-10 text-sm text-slate-900 placeholder:text-slate-400 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/25 dark:border-slate-600 dark:bg-slate-800/50 dark:text-white dark:placeholder:text-slate-500 dark:focus:bg-slate-900"
                        >
                        <button type="button" id="category-search-clear" class="absolute inset-y-0 end-0 hidden items-center pe-2 text-xs font-semibold text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200" aria-hidden="true">Limpiar</button>
                    </div>
                    <a href="{{ route('categories.create') }}" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        Nueva categoría
                    </a>
                </div>

                <div id="category-no-results" class="hidden border-b border-amber-100 bg-amber-50/90 px-5 py-4 text-center text-sm font-medium text-amber-900 dark:border-amber-900/30 dark:bg-amber-950/30 dark:text-amber-100" role="status"></div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 text-sm dark:divide-slate-800">
                        <thead>
                            <tr class="bg-slate-50/90 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:bg-slate-800/60 dark:text-slate-400">
                                <th scope="col" class="w-16 px-4 py-3 sm:px-6">#</th>
                                <th scope="col" class="px-4 py-3 sm:px-6">Nombre</th>
                                <th scope="col" class="px-4 py-3 text-end sm:px-6">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 dark:divide-slate-800/80">
                            @forelse ($categories as $category)
                                @php $hay = mb_strtolower($category->name); @endphp
                                <tr data-admin-row data-search-haystack="{{ e($hay) }}" class="transition hover:bg-indigo-50/40 dark:hover:bg-slate-800/50">
                                    <td class="whitespace-nowrap px-4 py-3 tabular-nums text-slate-400 dark:text-slate-500 sm:px-6">{{ $category->id }}</td>
                                    <td class="px-4 py-3 sm:px-6">
                                        <span class="inline-flex rounded-lg bg-slate-100 px-2.5 py-1 text-sm font-semibold text-slate-800 dark:bg-slate-800 dark:text-slate-100">{{ $category->name }}</span>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-3 text-end sm:px-6">
                                        <div class="inline-flex items-center justify-end gap-1">
                                            <a href="{{ route('categories.edit', $category) }}" class="inline-flex items-center justify-center rounded-lg p-2 text-indigo-600 transition hover:bg-indigo-50 dark:text-indigo-400 dark:hover:bg-indigo-950/40" title="Editar">
                                                <span class="sr-only">Editar</span>
                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                                            </a>
                                            <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar esta categoría?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center justify-center rounded-lg p-2 text-red-600 transition hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-950/30" title="Eliminar">
                                                    <span class="sr-only">Eliminar</span>
                                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">No hay categorías. Crea la primera.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <p class="border-t border-slate-100 px-5 py-3 text-center text-xs text-slate-500 dark:border-slate-800 dark:text-slate-400 sm:px-6">
                    Mostrando <span id="category-count-label">{{ $categories->count() }}</span> de {{ $categories->count() }} categorías
                </p>
            </div>
        </div>
    </div>

    @if($categories->isNotEmpty())
        <script>
            (function () {
                var input = document.getElementById('category-search');
                var clearBtn = document.getElementById('category-search-clear');
                var noResults = document.getElementById('category-no-results');
                var countLabel = document.getElementById('category-count-label');
                if (!input) return;

                var rows = document.querySelectorAll('tr[data-admin-row]');
                var total = rows.length;

                function filter() {
                    var q = input.value.trim().toLowerCase();
                    var visible = 0;
                    rows.forEach(function (row) {
                        var hay = row.getAttribute('data-search-haystack') || '';
                        var show = q === '' || hay.indexOf(q) !== -1;
                        row.classList.toggle('hidden', !show);
                        if (show) visible++;
                    });
                    if (noResults) {
                        noResults.classList.toggle('hidden', !(q !== '' && visible === 0));
                        if (q !== '' && visible === 0) noResults.textContent = 'Ninguna categoría coincide con «' + q + '».';
                    }
                    if (countLabel) countLabel.textContent = String(visible);
                    if (clearBtn) {
                        var hide = q === '';
                        clearBtn.classList.toggle('hidden', hide);
                        clearBtn.classList.toggle('inline-flex', !hide);
                    }
                }

                function clearSearch() {
                    input.value = '';
                    filter();
                    input.focus();
                }

                input.addEventListener('input', filter);
                input.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape') clearSearch();
                });
                if (clearBtn) clearBtn.addEventListener('click', clearSearch);
                filter();
            })();
        </script>
    @endif
</x-app-layout>
