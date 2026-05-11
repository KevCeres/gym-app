<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-900 leading-tight dark:text-gray-100">Nueva plantilla</h2>
            </div>
            <a href="{{ route('routine-templates.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">Volver</a>
        </div>
    </x-slot>

    <div class="min-h-[50vh] bg-gradient-to-b from-slate-50 via-white to-indigo-50/30 py-8 dark:from-gray-950 dark:via-gray-900 dark:to-indigo-950/20 sm:py-10">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-6 shadow-md dark:border-slate-700 dark:bg-slate-900 sm:p-8">
                <form action="{{ route('routine-templates.store') }}" method="POST" class="space-y-6" id="rt-form">
                    @csrf
                    <div>
                        <x-input-label for="name" value="Nombre de la plantilla" />
                        <x-text-input id="name" class="mt-1 block w-full" type="text" name="name" :value="old('name')" placeholder="" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <div class="flex items-center justify-between gap-2">
                            <x-input-label value="Ejercicios: " />
                            <button type="button" id="rt-add-row" class="text-sm font-semibold text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">+ Añadir ejercicio</button>
                        </div>

                        <div class="mt-3 overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-700">
                            <table class="min-w-full divide-y divide-slate-100 text-sm dark:divide-slate-800">
                                <thead class="bg-slate-50 dark:bg-slate-800/50">
                                    <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                        <th class="px-3 py-2">Ejercicio</th>
                                        <th class="w-24 px-3 py-2">Reps</th>
                                        <th class="w-24 px-3 py-2">Series</th>
                                        <th class="w-12 px-3 py-2"></th>
                                    </tr>
                                </thead>
                                <tbody id="rt-rows" class="divide-y divide-slate-50 dark:divide-slate-800">
                                    @php
                                        $oldItems = old('items');
                                        if (! is_array($oldItems) || count($oldItems) === 0) {
                                            $oldItems = [['exercise_id' => '', 'default_reps' => 10, 'default_series_count' => 1]];
                                        }
                                    @endphp
                                    @foreach ($oldItems as $idx => $row)
                                        <tr class="rt-row bg-white dark:bg-slate-900">
                                            <td class="px-3 py-2">
                                                <select name="items[{{ $idx }}][exercise_id]" class="block w-full min-w-[12rem] rounded-lg border-slate-200 text-sm dark:border-slate-600 dark:bg-slate-800" required>
                                                    <option value="" disabled @selected(!($row['exercise_id'] ?? ''))>— Elegir —</option>
                                                    @foreach ($exercises as $ex)
                                                        <option value="{{ $ex->id }}" @selected((string) ($row['exercise_id'] ?? '') === (string) $ex->id)>{{ $ex->name }} ({{ $ex->category->name ?? '—' }})</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="px-3 py-2">
                                                <input type="number" name="items[{{ $idx }}][default_reps]" value="{{ $row['default_reps'] ?? 10 }}" min="1" max="999" class="block w-full rounded-lg border-slate-200 text-sm dark:border-slate-600 dark:bg-slate-800" required />
                                            </td>
                                            <td class="px-3 py-2">
                                                <input type="number" name="items[{{ $idx }}][default_series_count]" value="{{ $row['default_series_count'] ?? 1 }}" min="1" max="30" class="block w-full rounded-lg border-slate-200 text-sm dark:border-slate-600 dark:bg-slate-800" required />
                                            </td>
                                            <td class="px-3 py-2 text-end">
                                                <button type="button" class="rt-remove inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-red-200 bg-white text-red-600 shadow-sm transition hover:border-red-300 hover:bg-red-50 hover:text-red-700 dark:border-red-900/50 dark:bg-slate-800 dark:text-red-400 dark:hover:border-red-800 dark:hover:bg-red-950/40" title="Quitar fila">
                                                    <span class="sr-only">Quitar</span>
                                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <x-input-error :messages="$errors->get('items')" class="mt-2" />
                    </div>

                    <div class="flex items-center gap-3">
                        <x-primary-button>Guardar plantilla</x-primary-button>
                        <a href="{{ route('routine-templates.index') }}" class="text-sm text-slate-600 hover:text-slate-900 dark:text-slate-400">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <template id="rt-row-template">
        <tr class="rt-row bg-white dark:bg-slate-900">
            <td class="px-3 py-2">
                <select name="items[__I__][exercise_id]" class="block w-full min-w-[12rem] rounded-lg border-slate-200 text-sm dark:border-slate-600 dark:bg-slate-800" required>
                    <option value="" disabled selected>— Elegir —</option>
                    @foreach ($exercises as $ex)
                        <option value="{{ $ex->id }}">{{ $ex->name }} ({{ $ex->category->name ?? '—' }})</option>
                    @endforeach
                </select>
            </td>
            <td class="px-3 py-2">
                <input type="number" name="items[__I__][default_reps]" value="10" min="1" max="999" class="block w-full rounded-lg border-slate-200 text-sm dark:border-slate-600 dark:bg-slate-800" required />
            </td>
            <td class="px-3 py-2">
                <input type="number" name="items[__I__][default_series_count]" value="1" min="1" max="30" class="block w-full rounded-lg border-slate-200 text-sm dark:border-slate-600 dark:bg-slate-800" required />
            </td>
            <td class="px-3 py-2 text-end">
                <button type="button" class="rt-remove inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-red-200 bg-white text-red-600 shadow-sm transition hover:border-red-300 hover:bg-red-50 hover:text-red-700 dark:border-red-900/50 dark:bg-slate-800 dark:text-red-400 dark:hover:border-red-800 dark:hover:bg-red-950/40" title="Quitar fila">
                    <span class="sr-only">Quitar</span>
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                </button>
            </td>
        </tr>
    </template>

    <script>
        (function () {
            var tbody = document.getElementById('rt-rows');
            var addBtn = document.getElementById('rt-add-row');
            var tpl = document.getElementById('rt-row-template');
            if (!tbody || !addBtn || !tpl) return;

            function reindex() {
                var rows = tbody.querySelectorAll('tr.rt-row');
                rows.forEach(function (row, i) {
                    row.querySelectorAll('[name]').forEach(function (el) {
                        el.name = el.name.replace(/items\[\d+]/, 'items[' + i + ']');
                    });
                });
            }

            function bindRemove(row) {
                var btn = row.querySelector('.rt-remove');
                if (btn) btn.addEventListener('click', function () {
                    if (tbody.querySelectorAll('tr.rt-row').length <= 1) return;
                    row.remove();
                    reindex();
                });
            }

            tbody.querySelectorAll('tr.rt-row').forEach(bindRemove);

            addBtn.addEventListener('click', function () {
                if (tbody.querySelectorAll('tr.rt-row').length >= 40) return;
                var html = tpl.innerHTML.replace(/__I__/g, '0');
                var wrap = document.createElement('tbody');
                wrap.innerHTML = html.trim();
                var row = wrap.firstElementChild;
                tbody.appendChild(row);
                reindex();
                bindRemove(row);
            });
        })();
    </script>
</x-app-layout>
