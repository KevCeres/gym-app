@props([
    'exercise',
    'large' => false,
])

@php
    $box = $large ? 'h-14 w-14 sm:h-16 sm:w-16' : 'h-11 w-11';
    $initial = $exercise?->name ? mb_strtoupper(mb_substr(trim($exercise->name), 0, 1)) : '?';
@endphp

@if($exercise?->image)
    <img
        src="{{ asset('storage/' . $exercise->image) }}"
        alt=""
        loading="lazy"
        {{ $attributes->class([$box, 'shrink-0 rounded-lg object-cover ring-1 ring-gray-200 dark:ring-gray-600']) }}
    >
@else
    <span
        aria-hidden="true"
        {{ $attributes->class([
            $box,
            'shrink-0 inline-flex items-center justify-center rounded-lg bg-slate-100 text-xs font-semibold text-slate-400 ring-1 ring-slate-200 dark:bg-slate-800 dark:text-slate-500 dark:ring-slate-600',
        ]) }}
        title="{{ $exercise?->name ?? '' }}"
    >{{ $initial }}</span>
@endif
