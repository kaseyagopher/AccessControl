@props(['label', 'value', 'icon' => null, 'color' => 'brand'])

@php
    $palette = match ($color) {
        'charcoal', 'violet' => ['icon' => 'bg-mmg-charcoal/10 text-mmg-charcoal', 'value' => 'text-mmg-charcoal'],
        'sky' => ['icon' => 'bg-brand-50 text-brand-600', 'value' => 'text-brand-600'],
        'amber' => ['icon' => 'bg-brand-100 text-brand-700', 'value' => 'text-brand-700'],
        'emerald' => ['icon' => 'bg-brand-50 text-brand-700', 'value' => 'text-brand-700'],
        'red', 'orange' => ['icon' => 'bg-brand-100 text-brand-800', 'value' => 'text-brand-800'],
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
