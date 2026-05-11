<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-900 leading-tight dark:text-gray-100">Editar plantilla</h2>
                <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">{{ $routineTemplate->name }}</p>
            </div>
            <a href="{{ route('routine-templates.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">Volver</a>
        </div>
    </x-slot>

    <div class="min-h-[50vh] bg-gradient-to-b from-slate-50 via-white to-indigo-50/30 py-8 dark:from-gray-950 dark:via-gray-900 dark:to-indigo-950/20 sm:py-10">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-6 shadow-md dark:border-slate-700 dark:bg-slate-900 sm:p-8">
                <form action="{{ route('routine-templates.update', $routineTemplate) }}" method="POST" class="space-y-6" id="rt-form">
                    @csrf
                    @method('PUT')
                    <div>
                        <x-input-label for="name" value="Nombre de la plantilla" />
                        <x-text-input id="name" class="mt-1 block w-full" type="text" name="name" :value="old('name', $routineTemplate->name)" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <div class="flex items-center justify-between gap-2">
                            <x-input-label value="Ejercicios (en orden)" />
                            <button type="button" id="rt-add-row" class="text-sm font-semibold text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">+ Añadir ejercicio</button>
                        </div>
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Las reps y series son sugerencias al usar la plantilla al registrar series.</p>

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
                                            $oldItems = $routineTemplate->items->map(fn ($i) => [
                                                'exercise_id' => $i->exercise_id,
                                                'default_reps' => $i->default_reps ?? 10,
                                                'default_series_count' => $i->default_series_count ?? 1,
                                            ])->all();
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
                                                <button type="button" class="rt-remove text-red-600 hover:text-red-500 text-xs font-semibold" title="Quitar">✕</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <x-input-error :messages="$errors->get('items')" class="mt-2" />
                    </div>

                    <div class="flex items-center gap-3">
                        <x-primary-button>Actualizar plantilla</x-primary-button>
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
                <button type="button" class="rt-remove text-red-600 hover:text-red-500 text-xs font-semibold" title="Quitar">✕</button>
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
