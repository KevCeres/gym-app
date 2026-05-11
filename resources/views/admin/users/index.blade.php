<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-900 leading-tight dark:text-gray-100">{{ __('Usuarios') }}</h2>
            <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">Cuentas del sistema (no incluye tu sesión actual)</p>
        </div>
    </x-slot>

    <div class="min-h-[50vh] bg-gradient-to-b from-slate-50 via-white to-indigo-50/30 py-8 dark:from-gray-950 dark:via-gray-900 dark:to-indigo-950/20 sm:py-10">
        <div class="mx-auto max-w-6xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="rounded-2xl border border-emerald-200/80 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-900 shadow-sm dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-200" role="status">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-md shadow-slate-200/40 ring-1 ring-slate-100 dark:border-slate-700 dark:bg-slate-900 dark:shadow-none dark:ring-slate-800">
                <div class="h-1 bg-gradient-to-r from-indigo-500 via-violet-500 to-fuchsia-500" aria-hidden="true"></div>

                <div class="flex flex-col gap-4 border-b border-slate-100 p-5 dark:border-slate-800 sm:flex-row sm:items-center sm:justify-between sm:p-6">
                    <div class="relative max-w-md flex-1" role="search">
                        <label for="user-search" class="sr-only">Buscar usuario</label>
                        <div class="pointer-events-none absolute inset-y-0 start-0 flex items-center ps-3">
                            <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                        </div>
                        <input
                            id="user-search"
                            type="search"
                            autocomplete="off"
                            placeholder="Buscar por nombre, correo o rol…"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50/80 py-2.5 ps-10 pe-10 text-sm text-slate-900 placeholder:text-slate-400 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/25 dark:border-slate-600 dark:bg-slate-800/50 dark:text-white dark:placeholder:text-slate-500 dark:focus:bg-slate-900"
                        >
                        <button type="button" id="user-search-clear" class="absolute inset-y-0 end-0 hidden items-center pe-2 text-xs font-semibold text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200" aria-hidden="true">Limpiar</button>
                    </div>
                    <a href="{{ route('users.create') }}" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Nuevo usuario
                    </a>
                </div>

                <div id="user-no-results" class="hidden border-b border-amber-100 bg-amber-50/90 px-5 py-4 text-center text-sm font-medium text-amber-900 dark:border-amber-900/30 dark:bg-amber-950/30 dark:text-amber-100" role="status"></div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 text-sm dark:divide-slate-800">
                        <thead>
                            <tr class="bg-slate-50/90 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:bg-slate-800/60 dark:text-slate-400">
                                <th scope="col" class="w-px px-4 py-3 sm:px-6">#</th>
                                <th scope="col" class="sr-only w-px px-4 py-3 sm:px-6">Avatar</th>
                                <th scope="col" class="px-4 py-3 sm:px-6">Nombre</th>
                                <th scope="col" class="hidden px-4 py-3 md:table-cell md:px-6">Correo</th>
                                <th scope="col" class="px-4 py-3 sm:px-6">Rol</th>
                                <th scope="col" class="px-4 py-3 text-end sm:px-6">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 dark:divide-slate-800/80">
                            @forelse ($users as $user)
                                @php
                                    $roleLabel = $user->role === 'admin' ? 'admin' : 'usuario';
                                    $hay = mb_strtolower($user->name.' '.$user->email.' '.$roleLabel);
                                @endphp
                                <tr data-admin-row data-search-haystack="{{ e($hay) }}" class="transition hover:bg-indigo-50/40 dark:hover:bg-slate-800/50">
                                    <td class="whitespace-nowrap px-4 py-3 tabular-nums text-slate-400 dark:text-slate-500 sm:px-6">{{ $user->id }}</td>
                                    <td class="px-4 py-3 sm:px-6">
                                        @if($user->avatar_url)
                                            <img src="{{ $user->avatar_url }}" alt="" class="h-10 w-10 rounded-full object-cover ring-2 ring-slate-100 dark:ring-slate-700" loading="lazy">
                                        @else
                                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-slate-200 text-sm font-bold text-slate-600 ring-2 ring-white dark:bg-slate-700 dark:text-slate-300 dark:ring-slate-800" aria-hidden="true">{{ mb_strtoupper(mb_substr(trim($user->name), 0, 1)) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 sm:px-6">
                                        <p class="font-semibold text-slate-900 dark:text-white">{{ $user->name }}</p>
                                        <p class="mt-0.5 text-xs text-slate-500 md:hidden dark:text-slate-400">{{ $user->email }}</p>
                                    </td>
                                    <td class="hidden px-4 py-3 text-slate-600 dark:text-slate-300 md:table-cell md:px-6">{{ $user->email }}</td>
                                    <td class="px-4 py-3 sm:px-6">
                                        <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $user->role === 'admin' ? 'bg-violet-100 text-violet-800 dark:bg-violet-950/50 dark:text-violet-200' : 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200' }}">
                                            {{ $user->role === 'admin' ? 'Admin' : 'Usuario' }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-3 text-end sm:px-6">
                                        <div class="inline-flex items-center justify-end gap-1">
                                            <a href="{{ route('users.edit', $user) }}" class="inline-flex items-center justify-center rounded-lg p-2 text-indigo-600 transition hover:bg-indigo-50 dark:text-indigo-400 dark:hover:bg-indigo-950/40" title="Editar">
                                                <span class="sr-only">Editar</span>
                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                                            </a>
                                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este usuario?');">
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
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">No hay otros usuarios. Crea uno con «Nuevo usuario».</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <p class="border-t border-slate-100 px-5 py-3 text-center text-xs text-slate-500 dark:border-slate-800 dark:text-slate-400 sm:px-6">
                    Mostrando <span id="user-count-label">{{ $users->count() }}</span> de {{ $users->count() }} usuarios
                </p>
            </div>
        </div>
    </div>

    @if($users->isNotEmpty())
        <script>
            (function () {
                var input = document.getElementById('user-search');
                var clearBtn = document.getElementById('user-search-clear');
                var noResults = document.getElementById('user-no-results');
                var countLabel = document.getElementById('user-count-label');
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
                        if (q !== '' && visible === 0) noResults.textContent = 'Ningún usuario coincide con «' + q + '».';
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
