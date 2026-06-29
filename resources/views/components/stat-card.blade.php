@props(['label', 'value', 'icon' => null, 'color' => 'brand'])

@php
    $palette = match ($color) {
        'violet' => ['icon' => 'bg-violet-50 text-violet-600', 'value' => 'text-violet-600'],
        'sky' => ['icon' => 'bg-sky-50 text-sky-600', 'value' => 'text-sky-600'],
        'amber' => ['icon' => 'bg-amber-50 text-amber-600', 'value' => 'text-amber-600'],
        'emerald' => ['icon' => 'bg-emerald-50 text-emerald-600', 'value' => 'text-emerald-600'],
        'red', 'orange' => ['icon' => 'bg-red-50 text-red-600', 'value' => 'text-red-600'],
        'charcoal' => ['icon' => 'bg-mmg-charcoal/10 text-mmg-charcoal', 'value' => 'text-mmg-charcoal'],
        default => ['icon' => 'bg-brand-50 text-brand-600', 'value' => 'text-brand-600'],
    };
@endphp

<div class="card flex flex-col gap-3">
    @if ($icon)
        <div class="flex h-11 w-11 items-center justify-center rounded-xl {{ $palette['icon'] }}">
            <x-nav-icon :name="$icon" class="h-5 w-5" />
        </div>
    @endif
    <div>
        <p class="text-sm font-medium text-slate-500">{{ $label }}</p>
        <p class="mt-1 text-3xl font-bold tracking-tight {{ $palette['value'] }}">{{ $value }}</p>
    </div>
</div>
