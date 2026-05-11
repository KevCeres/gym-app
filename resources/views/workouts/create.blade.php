<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-900 leading-tight dark:text-gray-100">Nueva serie</h2>
            </div>
            <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                {{-- Estilo pill: secundario (fondo claro + borde), como «Historial» --}}
                <a href="{{ route('routine-templates.index') }}" class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white px-6 py-2.5 text-sm font-semibold text-slate-900 shadow-sm transition hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:hover:bg-slate-700">
                    Plantillas
                </a>
                {{-- Estilo pill: primario (relleno oscuro + texto blanco), como «Nueva serie» --}}
                <a href="{{ route('workouts.progress') }}" class="inline-flex items-center justify-center rounded-full bg-slate-900 px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-100">
                    Progreso
                </a>
            </div>
        </div>
    </x-slot>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('workoutCreate', () => ({
                payload: null,
                exercises: [],
                templates: [],
                workoutDate: '',
                selectedTemplate: '',
                lines: [],

                init() {
                    var el = document.getElementById('workout-create-payload');
                    if (!el) return;
                    this.payload = JSON.parse(el.textContent);
                    this.exercises = this.payload.exercises || [];
                    this.templates = this.payload.templates || [];
                    this.workoutDate = this.payload.defaultDate || '';
                    this.lines = [this.blankLine()];
                    if (this.payload.prefillTemplateId) {
                        this.selectedTemplate = String(this.payload.prefillTemplateId);
                        this.applyTemplate(this.payload.prefillTemplateId);
                    }
                },

                blankLine() {
                    return {
                        exercise_id: null,
                        query: '',
                        open: false,
                        reps: 10,
                        series_count: 1,
                        weight: '',
                        highlighted: 0,
                    };
                },

                exerciseById(id) {
                    if (id == null || id === '') return null;
                    return this.exercises.find(function (e) { return Number(e.id) === Number(id); }) || null;
                },

                filterList(line) {
                    var q = (line.query || '').trim().toLowerCase();
                    if (!q) return this.exercises.slice(0, 15);
                    return this.exercises.filter(function (e) { return e.haystack.indexOf(q) !== -1; }).slice(0, 25);
                },

                selectExercise(line, ex) {
                    line.exercise_id = ex.id;
                    line.query = ex.name;
                    line.open = false;
                },

                onLineQueryInput(line) {
                    var ex = this.exerciseById(line.exercise_id);
                    if (ex && line.query === ex.name) {
                        line.open = true;
                        return;
                    }
                    line.exercise_id = null;
                    line.open = true;
                },

                applyTemplate(id) {
                    var t = this.templates.find(function (x) { return Number(x.id) === Number(id); });
                    if (!t) return;
                    this.lines = t.items.map(function (item) {
                        return {
                            exercise_id: item.exercise_id,
                            query: item.name,
                            open: false,
                            reps: item.default_reps,
                            series_count: item.default_series_count,
                            weight: '',
                            highlighted: 0,
                        };
                    });
                },

                onTemplateChange() {
                    if (!this.selectedTemplate) {
                        this.lines = [this.blankLine()];
                        return;
                    }
                    this.applyTemplate(Number(this.selectedTemplate));
                },

                lineVolume(line) {
                    var w = parseFloat(line.weight);
                    var ww = Number.isFinite(w) ? w : 0;
                    var r = parseInt(line.reps, 10) || 0;
                    var s = parseInt(line.series_count, 10) || 0;
                    return Math.round(ww * r * s * 100) / 100;
                },

                totalVolume() {
                    var sum = 0;
                    var self = this;
                    this.lines.forEach(function (l) { sum += self.lineVolume(l); });
                    return Math.round(sum * 100) / 100;
                },

                addLine() {
                    if (this.lines.length >= 40) return;
                    this.lines.push(this.blankLine());
                },

                removeLine(i) {
                    if (this.lines.length <= 1) return;
                    this.lines.splice(i, 1);
                },

                validateForm(evt) {
                    for (var i = 0; i < this.lines.length; i++) {
                        var l = this.lines[i];
                        if (!l.exercise_id) {
                            evt.preventDefault();
                            alert('Fila ' + (i + 1) + ': elige un ejercicio del buscador.');
                            return;
                        }
                        if (l.weight === '' || l.weight === null || Number(l.weight) < 0) {
                            evt.preventDefault();
                            alert('Fila ' + (i + 1) + ': indica el peso (kg).');
                            return;
                        }
                    }
                },
            }));
        });
    </script>
    <script type="application/json" id="workout-create-payload">{!! $workoutCreatePayloadJson !!}</script>

    <div class="min-h-[50vh] bg-gradient-to-b from-slate-50 via-white to-indigo-50/30 py-8 dark:from-gray-950 dark:via-gray-900 dark:to-indigo-950/20 sm:py-10" x-data="workoutCreate()">
        <div class="mx-auto max-w-3xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-md dark:border-slate-700 dark:bg-slate-900">
                <div class="h-1 bg-gradient-to-r from-indigo-500 via-violet-500 to-fuchsia-500" aria-hidden="true"></div>

                <form action="{{ route('workouts.store') }}" method="POST" class="space-y-6 p-6 sm:p-8" @@submit="validateForm">
                    @csrf

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <x-input-label for="plantilla" value="Cargar desde plantilla (opcional)" />
                            <select id="plantilla" x-model="selectedTemplate" @@change="onTemplateChange()" class="mt-1 block w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                                <option value="">— Agrega ejercicios o selecciona una plantilla —</option>
                                @foreach ($routineTemplates as $rt)
                                    <option value="{{ $rt->id }}">{{ $rt->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="workout_date" value="Fecha" />
                            <x-text-input id="workout_date" class="mt-1 block w-full" type="date" name="workout_date" x-model="workoutDate" required />
                            <x-input-error :messages="$errors->get('workout_date')" class="mt-2" />
                        </div>
                        <div class="flex items-end">
                            <p class="w-full rounded-xl border border-indigo-100 bg-indigo-50/80 px-4 py-3 text-sm dark:border-indigo-900/40 dark:bg-indigo-950/30">
                                <span class="font-semibold text-indigo-900 dark:text-indigo-100">Volumen total estimado:</span>
                                <span class="ms-1 tabular-nums font-bold text-indigo-700 dark:text-indigo-300" x-text="totalVolume().toLocaleString('es-ES', { minimumFractionDigits: 0, maximumFractionDigits: 2 })"></span>
                            </p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between gap-2">
                            <x-input-label value="Ejercicios y cargas" />
                            <button type="button" @@click="addLine()" class="text-sm font-semibold text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">+ Añadir ejercicio</button>
                        </div>

                        <template x-for="(line, index) in lines" :key="index">
                            <div class="rounded-2xl border border-slate-200 bg-slate-50/50 p-4 shadow-sm dark:border-slate-700 dark:bg-slate-800/40 sm:p-5">
                                <div class="flex items-start justify-between gap-2">
                                    <span class="text-xs font-bold uppercase tracking-wide text-slate-400 dark:text-slate-500" x-text="'Ejercicio ' + (index + 1)"></span>
                                    <button type="button" class="text-xs font-semibold text-red-600 hover:text-red-500 dark:text-red-400" @@click="removeLine(index)" x-show="lines.length > 1">Quitar</button>
                                </div>

                                <div class="mt-3 grid gap-4 sm:grid-cols-2">
                                    <div class="relative sm:col-span-2" @@click.outside="line.open = false">
                                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">Buscar ejercicio</label>
                                        <div class="relative mt-1">
                                            <input
                                                type="text"
                                                autocomplete="off"
                                                class="block w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-900 dark:text-white"
                                                placeholder="Nombre o categoría…"
                                                x-model="line.query"
                                                @@focus="line.open = true; onLineQueryInput(line)"
                                                @@input="onLineQueryInput(line)"
                                            />
                                            <div
                                                x-show="line.open && filterList(line).length"
                                                x-transition
                                                class="absolute z-20 mt-1 max-h-64 w-full overflow-auto rounded-xl border border-slate-200 bg-white py-1 shadow-lg dark:border-slate-600 dark:bg-slate-900"
                                            >
                                                <template x-for="ex in filterList(line)" :key="ex.id">
                                                    <button type="button" class="flex w-full items-center gap-3 px-3 py-2 text-left text-sm hover:bg-indigo-50 dark:hover:bg-slate-800" @@click="selectExercise(line, ex)">
                                                        <img x-show="ex.image" :src="ex.image" alt="" class="h-10 w-10 shrink-0 rounded-lg object-cover ring-1 ring-slate-200 dark:ring-slate-600" loading="lazy" />
                                                        <span x-show="!ex.image" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-xs font-bold text-slate-500 dark:bg-slate-700 dark:text-slate-400" x-text="(ex.name || '?').charAt(0).toUpperCase()"></span>
                                                        <span class="min-w-0">
                                                            <span class="block font-medium text-slate-900 dark:text-white" x-text="ex.name"></span>
                                                            <span class="block text-xs text-slate-500 dark:text-slate-400" x-text="ex.category || '—'"></span>
                                                        </span>
                                                    </button>
                                                </template>
                                            </div>
                                        </div>
                                        <input type="hidden" :name="'lines[' + index + '][exercise_id]'" x-bind:value="line.exercise_id != null ? line.exercise_id : ''" />
                                        <p class="mt-1 text-xs text-red-600 dark:text-red-400" x-show="!line.exercise_id && line.query">Selecciona un ejercicio de la lista.</p>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">Series</label>
                                        <input type="number" :name="'lines[' + index + '][series_count]'" x-model.number="line.series_count" min="1" max="30" required class="mt-1 block w-full rounded-xl border-slate-200 text-sm dark:border-slate-600 dark:bg-slate-900 dark:text-white" />
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">Reps</label>
                                        <input type="number" :name="'lines[' + index + '][reps]'" x-model.number="line.reps" min="1" max="999" required class="mt-1 block w-full rounded-xl border-slate-200 text-sm dark:border-slate-600 dark:bg-slate-900 dark:text-white" />
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">Peso (kg)</label>
                                        <input type="number" :name="'lines[' + index + '][weight]'" x-model="line.weight" step="0.01" min="0" required class="mt-1 block w-full rounded-xl border-slate-200 text-sm dark:border-slate-600 dark:bg-slate-900 dark:text-white" />
                                    </div>
                                    <div class="flex items-end">
                                        <p class="text-sm text-slate-600 dark:text-slate-300">
                                            Volumen línea: <strong class="tabular-nums text-indigo-700 dark:text-indigo-300" x-text="lineVolume(line).toLocaleString('es-ES', { minimumFractionDigits: 0, maximumFractionDigits: 2 })"></strong>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="rounded-xl border border-slate-100 bg-slate-50/80 p-4 dark:border-slate-800 dark:bg-slate-800/30">
                        <label class="inline-flex items-center gap-2 text-sm text-slate-700 dark:text-slate-300">
                            <input type="checkbox" name="mark_completed" value="1" class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800" {{ old('mark_completed') ? 'checked' : '' }} />
                            <span>Marcar <strong class="font-semibold text-emerald-700 dark:text-emerald-400">todas</strong> las líneas como completadas al guardar</span>
                        </label>
                    </div>

                    @if ($errors->any())
                        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-900 dark:bg-red-950/40 dark:text-red-200" role="alert">
                            <p class="font-semibold">Revisa el formulario.</p>
                            <ul class="mt-2 list-inside list-disc">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="flex flex-wrap items-center gap-3">
                        <x-primary-button>Guardar</x-primary-button>
                        <a href="{{ route('workouts.index') }}" class="text-sm text-slate-600 hover:text-slate-900 dark:text-slate-400">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
