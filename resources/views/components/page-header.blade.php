@props(['title', 'subtitle' => null, 'icon' => null, 'color' => 'brand'])

@php
    $palette = match ($color) {
        'violet' => 'bg-violet-50 text-violet-600',
        'sky' => 'bg-sky-50 text-sky-600',
        'amber' => 'bg-amber-50 text-amber-600',
        'emerald' => 'bg-emerald-50 text-emerald-600',
        'red' => 'bg-red-50 text-red-600',
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
