@props(['icon' => 'clipboard', 'color' => 'brand'])

@php
    $palette = match ($color) {
        'charcoal', 'violet' => 'bg-mmg-charcoal/10 text-mmg-charcoal ring-mmg-gray',
        'sky' => 'bg-brand-50 text-brand-600 ring-brand-100',
        'amber' => 'bg-brand-100 text-brand-700 ring-brand-200',
        'emerald' => 'bg-brand-50 text-brand-700 ring-brand-100',
        'red' => 'bg-brand-100 text-brand-800 ring-brand-200',
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
