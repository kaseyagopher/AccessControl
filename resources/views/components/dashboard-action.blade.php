@props(['href', 'title', 'description', 'icon', 'color' => 'brand'])

@php
    $palette = match ($color) {
        'violet' => 'bg-violet-50 text-violet-600 group-hover:bg-violet-600',
        'sky' => 'bg-sky-50 text-sky-600 group-hover:bg-sky-600',
        'amber' => 'bg-amber-50 text-amber-600 group-hover:bg-amber-600',
        'emerald' => 'bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600',
        'red' => 'bg-red-50 text-red-600 group-hover:bg-red-600',
        'charcoal' => 'bg-mmg-charcoal/10 text-mmg-charcoal group-hover:bg-mmg-charcoal',
        default => 'bg-brand-50 text-brand-600 group-hover:bg-brand-600',
    };
@endphp

<a href="{{ $href }}" class="card group flex items-start gap-4 transition hover:border-brand-200 hover:shadow-md">
    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl transition group-hover:text-white {{ $palette }}">
        <x-nav-icon :name="$icon" class="h-6 w-6" />
    </div>
    <div class="min-w-0">
        <p class="font-semibold text-slate-900 group-hover:text-brand-600">{{ $title }}</p>
        <p class="mt-1 text-sm text-slate-500">{{ $description }}</p>
    </div>
</a>
