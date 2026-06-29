@props(['type' => 'success'])

@php
    $styles = match($type) {
        'error' => 'border-red-200 bg-red-50 text-red-800',
        'warning' => 'border-amber-200 bg-amber-50 text-amber-800',
        default => 'border-emerald-200 bg-emerald-50 text-emerald-800',
    };
@endphp

<div {{ $attributes->merge(['class' => "mb-6 rounded-xl border px-4 py-3 text-sm font-medium {$styles}"]) }}>
    {{ $slot }}
</div>
