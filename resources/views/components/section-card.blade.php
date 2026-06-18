@props(['title', 'icon', 'color' => 'brand'])

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

<div {{ $attributes->merge(['class' => 'card']) }}>
    <div class="mb-4 flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-xl {{ $palette }}">
            <x-nav-icon :name="$icon" class="h-5 w-5" />
        </div>
        <h2 class="font-semibold text-slate-900">{{ $title }}</h2>
    </div>
    {{ $slot }}
</div>
