@props(['title', 'icon', 'color' => 'brand'])

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

<div {{ $attributes->merge(['class' => 'card']) }}>
    <div class="mb-4 flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-xl {{ $palette }}">
            <x-nav-icon :name="$icon" class="h-5 w-5" />
        </div>
        <h2 class="font-semibold text-slate-900">{{ $title }}</h2>
    </div>
    {{ $slot }}
</div>
