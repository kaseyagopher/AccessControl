@props(['title', 'subtitle' => null, 'icon' => null, 'color' => 'brand'])

@php
    $palette = match ($color) {
        'charcoal', 'violet' => 'bg-mmg-charcoal/10 text-mmg-charcoal',
        'sky' => 'bg-brand-50 text-brand-600',
        'amber' => 'bg-brand-100 text-brand-700',
        'emerald' => 'bg-brand-50 text-brand-700',
        'red' => 'bg-brand-100 text-brand-800',
        default => 'bg-brand-50 text-brand-600',
    };
@endphp

<div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div class="flex items-start gap-4">
        @if ($icon)
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl {{ $palette }} shadow-sm">
                <x-nav-icon :name="$icon" class="h-6 w-6" />
            </div>
        @endif
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">{{ $title }}</h1>
            @if ($subtitle)
                <p class="mt-1 text-sm text-slate-500">{{ $subtitle }}</p>
            @endif
        </div>
    </div>
    @isset($actions)
        <div class="flex flex-wrap items-center gap-2">{{ $actions }}</div>
    @endisset
</div>
