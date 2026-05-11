<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            {{ __('Panel de control') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('error'))
                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-6 sm:p-8">
                <p class="text-gray-900 text-lg">
                    {{ __('Bienvenido de nuevo,') }}
                    <span class="font-semibold text-gray-950">{{ Auth::user()->name }}</span>
                </p>
                <p class="mt-2 text-sm text-gray-500">
                    Rol:
                    <span class="inline-flex items-center rounded-md bg-gray-100 px-2 py-0.5 font-medium text-gray-700">{{ Auth::user()->role === 'admin' ? 'Administrador' : 'Usuario' }}</span>
                </p>

                <div class="mt-8 grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
                    <div class="rounded-xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white p-6 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-start gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-slate-900 text-white" aria-hidden="true">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                                </svg>
                            </span>
                            <div class="min-w-0">
                                <h3 class="font-semibold text-gray-900">Mis rutinas</h3>
                                <p class="mt-1 text-sm text-gray-600 leading-relaxed">Registra series, repeticiones y peso para seguir tu progreso.</p>
                                <a href="{{ route('workouts.index') }}" class="mt-4 inline-flex text-sm font-semibold text-slate-900 hover:text-slate-700">
                                    Ver historial →
                                </a>
                            </div>
                        </div>
                    </div>

                    @if(Auth::user()->role == 'admin')
                        <div class="rounded-xl border border-indigo-100 bg-gradient-to-b from-indigo-50/80 to-white p-6 shadow-sm hover:shadow-md transition-shadow md:col-span-2 lg:col-span-2">
                            <div class="flex items-start gap-3">
                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-indigo-600 text-white" aria-hidden="true">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </span>
                                <div class="min-w-0 flex-1">
                                    <h3 class="font-semibold text-indigo-950">Administración</h3>
                                    <p class="mt-1 text-sm text-indigo-900/70">Usuarios, categorías y ejercicios del catálogo.</p>
                                    <ul class="mt-5 grid gap-2 sm:grid-cols-3">
                                        <li>
                                            <a href="{{ route('users.index') }}" class="flex items-center gap-2 rounded-lg border border-indigo-100 bg-white/80 px-3 py-2.5 text-sm font-medium text-indigo-900 hover:bg-indigo-50 transition-colors">
                                                <svg class="h-4 w-4 text-indigo-600 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.438 0 9.337 9.337 0 0 0 4.121.952 9.38 9.38 0 0 0 2.625-.372M12 21a9 9 0 1 0-9-9 9 9 0 0 0 9 9Zm0 0c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3 7.5 7.03 7.5 12 9.515 21 12 21Z" /></svg>
                                                Usuarios
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('categories.index') }}" class="flex items-center gap-2 rounded-lg border border-indigo-100 bg-white/80 px-3 py-2.5 text-sm font-medium text-indigo-900 hover:bg-indigo-50 transition-colors">
                                                <svg class="h-4 w-4 text-indigo-600 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" /></svg>
                                                Categorías
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('exercises.index') }}" class="flex items-center gap-2 rounded-lg border border-indigo-100 bg-white/80 px-3 py-2.5 text-sm font-medium text-indigo-900 hover:bg-indigo-50 transition-colors">
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
