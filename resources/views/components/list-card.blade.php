@props(['icon' => 'clipboard', 'color' => 'brand'])

@php
    $palette = match ($color) {
        'violet' => 'bg-violet-50 text-violet-600 ring-violet-100',
        'sky' => 'bg-sky-50 text-sky-600 ring-sky-100',
        'amber' => 'bg-amber-50 text-amber-600 ring-amber-100',
        'emerald' => 'bg-emerald-50 text-emerald-600 ring-emerald-100',
        'red' => 'bg-red-50 text-red-600 ring-red-100',
        'charcoal' => 'bg-mmg-charcoal/10 text-mmg-charcoal ring-mmg-gray',
        default => 'bg-brand-50 text-brand-600 ring-brand-100',
    };
@endphp

<div {{ $attributes->merge(['class' => 'card flex flex-col gap-4 sm:flex-row sm:items-center']) }}>
    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl ring-1 {{ $palette }}">
        <x-nav-icon :name="$icon" class="h-5 w-5" />
    </div>
    <div class="min-w-0 flex-1">
        {{ $slot }}
    </div>
    @isset($aside)
        <div class="flex shrink-0 flex-wrap items-center gap-2 sm:justify-end">
            {{ $aside }}
        </div>
    @endisset
</div>
