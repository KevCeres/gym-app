<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-900 leading-tight dark:text-gray-100">Plantillas de rutina</h2>
                <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">Define bloques de ejercicios y cárgalos al registrar series</p>
            </div>
            <a href="{{ route('routine-templates.create') }}" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Nueva plantilla
            </a>
        </div>
    </x-slot>

    <div class="min-h-[50vh] bg-gradient-to-b from-slate-50 via-white to-indigo-50/30 py-8 dark:from-gray-950 dark:via-gray-900 dark:to-indigo-950/20 sm:py-10">
        <div class="mx-auto max-w-4xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="rounded-2xl border border-emerald-200/80 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-900 shadow-sm dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-200" role="status">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-md dark:border-slate-700 dark:bg-slate-900">
                <div class="h-1 bg-gradient-to-r from-indigo-500 via-violet-500 to-fuchsia-500" aria-hidden="true"></div>
                <div class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($templates as $template)
                        <div class="flex flex-col gap-3 p-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                            <div>
                                <h3 class="font-semibold text-slate-900 dark:text-white">{{ $template->name }}</h3>
                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $template->items_count }} ejercicios</p>
                            </div>
                            <div class="flex flex-wrap items-center gap-2">
                                <a href="{{ route('workouts.create', ['plantilla' => $template->id]) }}" class="inline-flex items-center rounded-lg border border-indigo-200 bg-indigo-50 px-3 py-1.5 text-xs font-semibold text-indigo-800 hover:bg-indigo-100 dark:border-indigo-800 dark:bg-indigo-950/40 dark:text-indigo-200">Usar al registrar</a>
                                <a href="{{ route('routine-templates.edit', $template) }}" class="inline-flex items-center rounded-lg p-2 text-indigo-600 hover:bg-indigo-50 dark:text-indigo-400 dark:hover:bg-indigo-950/40" title="Editar">
                                    <span class="sr-only">Editar</span>
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                                </a>
                                <form action="{{ route('routine-templates.destroy', $template) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar esta plantilla?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center rounded-lg p-2 text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-950/30" title="Eliminar">
                                        <span class="sr-only">Eliminar</span>
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center text-slate-600 dark:text-slate-400">
                            <p>No tienes plantillas todavía.</p>
                            <a href="{{ route('routine-templates.create') }}" class="mt-4 inline-block text-sm font-semibold text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">Crear la primera</a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
