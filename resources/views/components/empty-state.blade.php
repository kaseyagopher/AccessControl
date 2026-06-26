@props(['icon' => 'inbox', 'color' => 'brand', 'message' => 'Aucun élément trouvé.'])

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

<div {{ $attributes->merge(['class' => 'card col-span-full flex flex-col items-center justify-center py-12 text-center']) }}>
    <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl {{ $palette }}">
        <x-nav-icon :name="$icon" class="h-7 w-7" />
    </div>
    <p class="text-sm font-medium text-slate-500">{{ $message }}</p>
    @isset($action)
        <div class="mt-4">{{ $action }}</div>
    @endisset
</div>
