@props(['icon' => 'inbox', 'color' => 'brand', 'message' => 'Aucun élément trouvé.'])

@php
    $palette = match ($color) {
        'violet' => 'bg-violet-50 text-violet-500',
        'sky' => 'bg-sky-50 text-sky-500',
        'amber' => 'bg-amber-50 text-amber-500',
        'emerald' => 'bg-emerald-50 text-emerald-500',
        default => 'bg-brand-50 text-brand-500',
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
