<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight dark:text-gray-100">
            {{ __('Inicio') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('error'))
                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-900 dark:bg-red-950/40 dark:text-red-200" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-6 sm:p-8 dark:bg-gray-900 dark:border-gray-800">
                <div class="flex items-center gap-4">
                    @if (Auth::user()->avatar_url)
                        <img src="{{ Auth::user()->avatar_url }}" alt="" class="h-14 w-14 shrink-0 rounded-full object-cover ring-1 ring-gray-200 dark:ring-gray-600">
                    @else
                        <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-gray-200 text-xl font-semibold text-gray-700 dark:bg-gray-700 dark:text-gray-200">{{ strtoupper(mb_substr(Auth::user()->name, 0, 1)) }}</span>
                    @endif
                    <p class="text-gray-900 text-lg dark:text-gray-100">
                        <span class="font-semibold text-gray-950 dark:text-white">{{ Auth::user()->name }}</span>
                        <span class="text-gray-500 font-normal text-base ms-2 dark:text-gray-400">{{ Auth::user()->role === 'admin' ? 'Admin' : 'Usuario' }}</span>
                    </p>
                </div>

                <div class="mt-8 grid grid-cols-1 gap-5 lg:grid-cols-3">
                    @if(Auth::user()->role === 'user')
                        @if(($workoutStreak ?? null) !== null)
                            <div class="rounded-xl border border-amber-200 bg-gradient-to-b from-amber-50 to-white p-6 shadow-sm dark:border-amber-900/50 dark:from-amber-950/30 dark:to-gray-900 lg:col-span-1">
                                <h3 class="font-semibold text-gray-900 dark:text-gray-100">Racha</h3>
                                <p class="mt-2 text-3xl font-bold tabular-nums text-amber-700 dark:text-amber-400">{{ $workoutStreak }} <span class="text-base font-semibold text-gray-600 dark:text-gray-400">{{ $workoutStreak === 1 ? 'día' : 'días' }}</span></p>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Días consecutivos con registro, desde el más reciente.</p>
                            </div>
                        @endif
                        <div class="rounded-xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white p-6 shadow-sm hover:shadow-md transition-shadow dark:border-slate-700 dark:from-slate-900/80 dark:to-gray-900 lg:col-span-1">
                            <div class="flex items-start gap-3">
                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-slate-900 text-white" aria-hidden="true">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                                    </svg>
                                </span>
                                <div class="min-w-0">
                                    <h3 class="font-semibold text-gray-900 dark:text-gray-100">Rutinas</h3>
                                    <div class="mt-4 flex flex-wrap gap-x-4 gap-y-2 text-sm font-semibold">
                                        <a href="{{ route('workouts.index') }}" class="text-slate-900 hover:text-slate-700 dark:text-slate-200 dark:hover:text-white">Historial</a>
                                        <a href="{{ route('workouts.progress') }}" class="text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">Progreso</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(Auth::user()->role == 'admin')
                        <div class="rounded-xl border border-indigo-100 bg-gradient-to-b from-indigo-50/80 to-white p-6 shadow-sm hover:shadow-md transition-shadow dark:border-indigo-900/40 dark:from-indigo-950/40 dark:to-gray-900 lg:col-span-3">
                            <div class="flex items-start gap-3">
                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-indigo-600 text-white" aria-hidden="true">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </span>
                                <div class="min-w-0 flex-1">
                                    <h3 class="font-semibold text-indigo-950 dark:text-indigo-200">Administración</h3>
                                    <ul class="mt-4 grid gap-2 sm:grid-cols-3">
                                        <li>
                                            <a href="{{ route('users.index') }}" class="flex items-center gap-2 rounded-lg border border-indigo-100 bg-white/80 px-3 py-2.5 text-sm font-medium text-indigo-900 hover:bg-indigo-50 transition-colors dark:border-indigo-800 dark:bg-gray-800/80 dark:text-indigo-100 dark:hover:bg-indigo-950/50">
                                                <svg class="h-4 w-4 text-indigo-600 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.438 0 9.337 9.337 0 0 0 4.121.952 9.38 9.38 0 0 0 2.625-.372M12 21a9 9 0 1 0-9-9 9 9 0 0 0 9 9Zm0 0c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3 7.5 7.03 7.5 12 9.515 21 12 21Z" /></svg>
                                                Usuarios
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('categories.index') }}" class="flex items-center gap-2 rounded-lg border border-indigo-100 bg-white/80 px-3 py-2.5 text-sm font-medium text-indigo-900 hover:bg-indigo-50 transition-colors dark:border-indigo-800 dark:bg-gray-800/80 dark:text-indigo-100 dark:hover:bg-indigo-950/50">
                                                <svg class="h-4 w-4 text-indigo-600 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" /></svg>
                                                Categorías
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('exercises.index') }}" class="flex items-center gap-2 rounded-lg border border-indigo-100 bg-white/80 px-3 py-2.5 text-sm font-medium text-indigo-900 hover:bg-indigo-50 transition-colors dark:border-indigo-800 dark:bg-gray-800/80 dark:text-indigo-100 dark:hover:bg-indigo-950/50">
                                                <svg class="h-4 w-4 text-indigo-600 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M6 3v18m3-18v18m3-18v18m3-18v18m3-18v18M9 3.75h7.5M9 16.5h7.5" /></svg>
                                                Ejercicios
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
