@php
    $hour = (int) now()->format('G');
    $greeting = $hour < 12 ? 'Buenos días' : ($hour < 20 ? 'Buenas tardes' : 'Buenas noches');
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-0.5 sm:flex-row sm:items-end sm:justify-between sm:gap-4">
            <div>
                <p class="text-xs font-medium uppercase tracking-wider text-indigo-600 dark:text-indigo-400">{{ $greeting }}</p>
                <h2 class="font-semibold text-xl text-gray-900 leading-tight dark:text-gray-100">
                    {{ __('Inicio') }}
                </h2>
            </div>
            <p class="text-sm capitalize text-slate-500 dark:text-slate-400">{{ now()->locale('es')->isoFormat('dddd D [de] MMMM [de] YYYY') }}</p>
        </div>
    </x-slot>

    <div class="relative min-h-[50vh] overflow-hidden bg-gradient-to-b from-slate-50 via-white to-indigo-50/40 py-10 dark:from-gray-950 dark:via-gray-900 dark:to-indigo-950/25">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_80%_50%_at_50%_-20%,rgba(99,102,241,0.15),transparent)] dark:bg-[radial-gradient(ellipse_80%_50%_at_50%_-20%,rgba(99,102,241,0.12),transparent)]" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -right-24 top-32 h-72 w-72 rounded-full bg-fuchsia-200/30 blur-3xl dark:bg-fuchsia-900/20" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -left-20 bottom-0 h-64 w-64 rounded-full bg-indigo-200/25 blur-3xl dark:bg-indigo-900/15" aria-hidden="true"></div>

        <div class="relative z-10 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            @if (session('error'))
                <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 shadow-sm dark:border-red-900 dark:bg-red-950/40 dark:text-red-200" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Perfil --}}
            <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white/90 shadow-lg shadow-indigo-100/30 ring-1 ring-slate-100 backdrop-blur-sm dark:border-slate-700 dark:bg-slate-900/90 dark:shadow-none dark:ring-slate-800">
                <div class="h-1.5 bg-gradient-to-r from-indigo-500 via-violet-500 to-fuchsia-500" aria-hidden="true"></div>
                <div class="p-6 sm:p-8">
                    <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:gap-6">
                            @if (Auth::user()->avatar_url)
                                <img src="{{ Auth::user()->avatar_url }}" alt="" class="h-20 w-20 shrink-0 rounded-2xl object-cover shadow-md ring-4 ring-white dark:ring-slate-800">
                            @else
                                <span class="flex h-20 w-20 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-600 text-2xl font-bold text-white shadow-lg shadow-indigo-500/30">{{ strtoupper(mb_substr(Auth::user()->name, 0, 1)) }}</span>
                            @endif
                            <div>
                                <p class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">{{ Auth::user()->name }}</p>
                                <p class="mt-2 inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                                    <span class="h-1.5 w-1.5 rounded-full {{ Auth::user()->role === 'admin' ? 'bg-violet-500' : 'bg-emerald-500' }}" aria-hidden="true"></span>
                                    {{ Auth::user()->role === 'admin' ? 'Administrador' : 'Atleta' }}
                                </p>
                            </div>
                        </div>
                        @if ($athleteStats)
                            <div class="flex flex-wrap gap-3 sm:justify-end">
                                <div class="rounded-xl border border-slate-200/90 bg-slate-50/80 px-4 py-3 text-center dark:border-slate-600 dark:bg-slate-800/50">
                                    <p class="text-2xl font-bold tabular-nums text-slate-900 dark:text-white">{{ number_format($athleteStats['workout_rows']) }}</p>
                                    <p class="text-xs font-medium text-slate-500 dark:text-slate-400">Líneas de serie</p>
                                </div>
                                <div class="rounded-xl border border-violet-200/80 bg-violet-50/80 px-4 py-3 text-center dark:border-violet-800/50 dark:bg-violet-950/30">
                                    <p class="text-2xl font-bold tabular-nums text-violet-900 dark:text-violet-100">{{ number_format($athleteStats['templates']) }}</p>
                                    <p class="text-xs font-medium text-violet-700 dark:text-violet-300">Plantillas</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if ($adminStats)
                {{-- Resumen admin --}}
                <div>
                    <h3 class="mb-4 text-sm font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Resumen del sistema</h3>
                    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
                        <div class="group relative overflow-hidden rounded-2xl border border-slate-200/90 bg-white p-5 shadow-md transition hover:-translate-y-0.5 hover:shadow-lg dark:border-slate-700 dark:bg-slate-900">
                            <div class="absolute right-3 top-3 rounded-lg bg-indigo-100 p-2 text-indigo-600 dark:bg-indigo-950 dark:text-indigo-400">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.438 0 9.337 9.337 0 0 0 4.121.952 9.38 9.38 0 0 0 2.625-.372M12 21a9 9 0 1 0-9-9 9 9 0 0 0 9 9Zm0 0c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3 7.5 7.03 7.5 12 9.515 21 12 21Z" /></svg>
                            </div>
                            <p class="text-xs font-medium text-slate-500 dark:text-slate-400">Usuarios</p>
                            <p class="mt-2 text-3xl font-bold tabular-nums tracking-tight text-slate-900 dark:text-white">{{ number_format($adminStats['users']) }}</p>
                        </div>
                        <div class="group relative overflow-hidden rounded-2xl border border-slate-200/90 bg-white p-5 shadow-md transition hover:-translate-y-0.5 hover:shadow-lg dark:border-slate-700 dark:bg-slate-900">
                            <div class="absolute right-3 top-3 rounded-lg bg-violet-100 p-2 text-violet-600 dark:bg-violet-950 dark:text-violet-400">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" /></svg>
                            </div>
                            <p class="text-xs font-medium text-slate-500 dark:text-slate-400">Categorías</p>
                            <p class="mt-2 text-3xl font-bold tabular-nums tracking-tight text-slate-900 dark:text-white">{{ number_format($adminStats['categories']) }}</p>
                        </div>
                        <div class="group relative overflow-hidden rounded-2xl border border-slate-200/90 bg-white p-5 shadow-md transition hover:-translate-y-0.5 hover:shadow-lg dark:border-slate-700 dark:bg-slate-900">
                            <div class="absolute right-3 top-3 rounded-lg bg-fuchsia-100 p-2 text-fuchsia-600 dark:bg-fuchsia-950 dark:text-fuchsia-400">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M6 3v18m3-18v18m3-18v18m3-18v18m3-18v18M9 3.75h7.5M9 16.5h7.5" /></svg>
                            </div>
                            <p class="text-xs font-medium text-slate-500 dark:text-slate-400">Ejercicios</p>
                            <p class="mt-2 text-3xl font-bold tabular-nums tracking-tight text-slate-900 dark:text-white">{{ number_format($adminStats['exercises']) }}</p>
                        </div>
                        <div class="group relative overflow-hidden rounded-2xl border border-slate-200/90 bg-white p-5 shadow-md transition hover:-translate-y-0.5 hover:shadow-lg dark:border-slate-700 dark:bg-slate-900">
                            <div class="absolute right-3 top-3 rounded-lg bg-emerald-100 p-2 text-emerald-600 dark:bg-emerald-950 dark:text-emerald-400">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                            </div>
                            <p class="text-xs font-medium text-slate-500 dark:text-slate-400">Registros de serie</p>
                            <p class="mt-2 text-3xl font-bold tabular-nums tracking-tight text-slate-900 dark:text-white">{{ number_format($adminStats['workouts']) }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 gap-6 lg:gap-8">
                @if (Auth::user()->role === 'user')
                    <div class="overflow-hidden rounded-2xl border border-slate-200/90 bg-gradient-to-br from-white to-slate-50/90 p-6 shadow-lg shadow-slate-200/40 ring-1 ring-slate-100 dark:border-slate-700 dark:from-slate-900 dark:to-slate-900/80 dark:shadow-none dark:ring-slate-800 sm:p-8">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex items-center gap-3">
                                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-slate-900 text-white shadow-md dark:bg-slate-100 dark:text-slate-900" aria-hidden="true">
                                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                                    </svg>
                                </span>
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">Tu entrenamiento</h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">Historial, progreso y plantillas</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                            <a href="{{ route('workouts.index') }}" class="group flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm ring-1 ring-slate-100 transition hover:-translate-y-0.5 hover:border-slate-300 hover:shadow-md dark:border-slate-600 dark:bg-slate-800/50 dark:ring-slate-700 dark:hover:border-slate-500">
                                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-200" aria-hidden="true">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                </span>
                                <span class="min-w-0 text-left">
                                    <span class="block font-semibold text-slate-900 group-hover:text-slate-700 dark:text-white dark:group-hover:text-slate-100">Historial</span>
                                    <span class="mt-0.5 block text-xs text-slate-500 dark:text-slate-400">Series por día</span>
                                </span>
                            </a>
                            <a href="{{ route('workouts.progress') }}" class="group flex items-center gap-4 rounded-2xl border border-indigo-200/80 bg-gradient-to-br from-indigo-50 to-white p-4 shadow-sm ring-1 ring-indigo-100/80 transition hover:-translate-y-0.5 hover:shadow-md dark:border-indigo-800/60 dark:from-indigo-950/40 dark:to-slate-900 dark:ring-indigo-900/40">
                                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-indigo-600 text-white shadow-sm" aria-hidden="true">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" /></svg>
                                </span>
                                <span class="min-w-0 text-left">
                                    <span class="block font-semibold text-indigo-950 group-hover:text-indigo-800 dark:text-indigo-100">Progreso</span>
                                    <span class="mt-0.5 block text-xs text-indigo-700/80 dark:text-indigo-300/80">Por ejercicio</span>
                                </span>
                            </a>
                            <a href="{{ route('workouts.create') }}" class="group flex items-center gap-4 rounded-2xl border border-emerald-300/80 bg-gradient-to-br from-emerald-50 to-white p-4 shadow-sm ring-1 ring-emerald-100 transition hover:-translate-y-0.5 hover:shadow-md dark:border-emerald-800/50 dark:from-emerald-950/30 dark:to-slate-900 dark:ring-emerald-900/30">
                                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-600 text-white shadow-sm" aria-hidden="true">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                </span>
                                <span class="min-w-0 text-left">
                                    <span class="block font-semibold text-emerald-950 group-hover:text-emerald-900 dark:text-emerald-100">Nueva serie</span>
                                    <span class="mt-0.5 block text-xs text-emerald-800/80 dark:text-emerald-300/80">Registrar carga</span>
                                </span>
                            </a>
                            <a href="{{ route('routine-templates.index') }}" class="group flex items-center gap-4 rounded-2xl border border-violet-200/80 bg-gradient-to-br from-violet-50 to-white p-4 shadow-sm ring-1 ring-violet-100 transition hover:-translate-y-0.5 hover:shadow-md dark:border-violet-800/50 dark:from-violet-950/30 dark:to-slate-900 dark:ring-violet-900/30">
                                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-violet-600 text-white shadow-sm" aria-hidden="true">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" /></svg>
                                </span>
                                <span class="min-w-0 text-left">
                                    <span class="block font-semibold text-violet-950 group-hover:text-violet-900 dark:text-violet-100">Plantillas</span>
                                    <span class="mt-0.5 block text-xs text-violet-800/80 dark:text-violet-300/80">Rutinas guardadas</span>
                                </span>
                            </a>
                        </div>
                    </div>
                @endif

                @if (Auth::user()->role === 'admin')
                    <div class="overflow-hidden rounded-2xl border border-indigo-200/80 bg-gradient-to-br from-indigo-50/90 via-white to-violet-50/50 p-6 shadow-lg shadow-indigo-100/30 ring-1 ring-indigo-100/60 dark:border-indigo-900/50 dark:from-indigo-950/50 dark:via-slate-900 dark:to-violet-950/30 dark:shadow-none dark:ring-indigo-900/40 sm:p-8">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex items-center gap-3">
                                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-indigo-600 text-white shadow-lg shadow-indigo-600/25" aria-hidden="true">
                                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </span>
                                <div>
                                    <h3 class="text-lg font-bold text-indigo-950 dark:text-indigo-100">Administración</h3>
                                    <p class="text-sm text-indigo-800/80 dark:text-indigo-300/80">Gestiona usuarios, categorías y ejercicios del catálogo</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 grid grid-cols-1 gap-3 sm:grid-cols-3">
                            <a href="{{ route('users.index') }}" class="group flex flex-col gap-3 rounded-2xl border border-white/80 bg-white/90 p-5 shadow-sm ring-1 ring-indigo-100/80 transition hover:-translate-y-0.5 hover:shadow-md dark:border-slate-700 dark:bg-slate-800/80 dark:ring-slate-700">
                                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-100 text-indigo-700 transition group-hover:bg-indigo-600 group-hover:text-white dark:bg-indigo-950 dark:text-indigo-300 dark:group-hover:bg-indigo-600 dark:group-hover:text-white" aria-hidden="true">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.438 0 9.337 9.337 0 0 0 4.121.952 9.38 9.38 0 0 0 2.625-.372M12 21a9 9 0 1 0-9-9 9 9 0 0 0 9 9Zm0 0c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3 7.5 7.03 7.5 12 9.515 21 12 21Z" /></svg>
                                </span>
                                <div>
                                    <span class="block font-semibold text-slate-900 group-hover:text-indigo-800 dark:text-white dark:group-hover:text-indigo-200">Usuarios</span>
                                    <span class="mt-1 block text-xs text-slate-500 dark:text-slate-400">Cuentas y roles</span>
                                </div>
                            </a>
                            <a href="{{ route('categories.index') }}" class="group flex flex-col gap-3 rounded-2xl border border-white/80 bg-white/90 p-5 shadow-sm ring-1 ring-violet-100/80 transition hover:-translate-y-0.5 hover:shadow-md dark:border-slate-700 dark:bg-slate-800/80 dark:ring-slate-700">
                                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-violet-100 text-violet-700 transition group-hover:bg-violet-600 group-hover:text-white dark:bg-violet-950 dark:text-violet-300 dark:group-hover:bg-violet-600 dark:group-hover:text-white" aria-hidden="true">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" /></svg>
                                </span>
                                <div>
                                    <span class="block font-semibold text-slate-900 group-hover:text-violet-800 dark:text-white dark:group-hover:text-violet-200">Categorías</span>
                                    <span class="mt-1 block text-xs text-slate-500 dark:text-slate-400">Organizar el catálogo</span>
                                </div>
                            </a>
                            <a href="{{ route('exercises.index') }}" class="group flex flex-col gap-3 rounded-2xl border border-white/80 bg-white/90 p-5 shadow-sm ring-1 ring-fuchsia-100/80 transition hover:-translate-y-0.5 hover:shadow-md dark:border-slate-700 dark:bg-slate-800/80 dark:ring-slate-700">
                                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-fuchsia-100 text-fuchsia-700 transition group-hover:bg-fuchsia-600 group-hover:text-white dark:bg-fuchsia-950 dark:text-fuchsia-300 dark:group-hover:bg-fuchsia-600 dark:group-hover:text-white" aria-hidden="true">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M6 3v18m3-18v18m3-18v18m3-18v18m3-18v18M9 3.75h7.5M9 16.5h7.5" /></svg>
                                </span>
                                <div>
                                    <span class="block font-semibold text-slate-900 group-hover:text-fuchsia-800 dark:text-white dark:group-hover:text-fuchsia-200">Ejercicios</span>
                                    <span class="mt-1 block text-xs text-slate-500 dark:text-slate-400">Movimientos disponibles</span>
                                </div>
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
