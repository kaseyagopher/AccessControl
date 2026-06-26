@props(['type' => 'success'])

@php
    $styles = match($type) {
        'error' => 'border-brand-300 bg-brand-50 text-brand-800',
        'warning' => 'border-brand-200 bg-brand-100 text-brand-800',
        default => 'border-brand-200 bg-brand-50 text-brand-700',
    };
@endphp

<div {{ $attributes->merge(['class' => "mb-6 rounded-xl border px-4 py-3 text-sm font-medium {$styles}"]) }}>
    {{ $slot }}
</div>
