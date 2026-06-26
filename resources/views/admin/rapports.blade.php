@extends('layouts.simple')

@section('title', 'Rapports')

@section('content')
<x-page-header title="Rapports et statistiques" icon="chart" color="amber" />

<x-section-card title="Filtrer la période" icon="clock" color="sky" class="mb-6">
    <form method="GET" action="{{ route('admin.rapports') }}" class="flex flex-col gap-4 sm:flex-row sm:items-end">
        <div class="flex-1"><label class="mb-1.5 block text-sm font-medium">Du</label><input type="date" name="debut" value="{{ $debut }}" class="input-field"></div>
        <div class="flex-1"><label class="mb-1.5 block text-sm font-medium">Au</label><input type="date" name="fin" value="{{ $fin }}" class="input-field"></div>
        <button type="submit" class="btn-primary inline-flex items-center gap-2">
            <x-nav-icon name="search" class="h-4 w-4" /> Filtrer
        </button>
    </form>
</x-section-card>

<div class="mb-8 grid gap-4 sm:grid-cols-3">
    <x-stat-card label="Total visites" :value="$stats['total_visiteurs']" icon="clipboard" color="brand" />
    <x-stat-card label="Validées" :value="$stats['validees']" icon="check-circle" color="emerald" />
    <x-stat-card label="Refusées" :value="$stats['refusees']" icon="x-circle" color="red" />
</div>

<div class="grid gap-6 lg:grid-cols-2">
    <x-section-card title="Par superviseur" icon="users" color="violet">
        <ul class="space-y-2">
            @forelse ($stats['par_superviseur'] as $row)
                <li class="flex items-center justify-between rounded-xl bg-mmg-charcoal/5 px-3 py-2.5 text-sm">
                    <span class="flex items-center gap-2 text-slate-700">
                        <x-nav-icon name="user" class="h-4 w-4 text-mmg-charcoal" />
                        {{ $row->superviseur->name ?? 'N/A' }}
                    </span>
                    <span class="rounded-lg bg-mmg-charcoal/10 px-2 py-0.5 font-semibold text-mmg-charcoal">{{ $row->total }}</span>
                </li>
            @empty
                <li class="text-slate-500">Aucune donnée</li>
            @endforelse
        </ul>
    </x-section-card>
    <x-section-card title="Entreprises fréquentes" icon="building" color="sky">
        <ul class="space-y-2">
            @forelse ($stats['entreprises'] as $row)
                <li class="flex items-center justify-between rounded-xl bg-brand-50/80 px-3 py-2.5 text-sm">
                    <span class="flex items-center gap-2 text-slate-700">
                        <x-nav-icon name="building" class="h-4 w-4 text-brand-600" />
                        {{ $row->entreprise }}
                    </span>
                    <span class="rounded-lg bg-brand-100 px-2 py-0.5 font-semibold text-brand-700">{{ $row->total }}</span>
                </li>
            @empty
                <li class="text-slate-500">Aucune donnée</li>
            @endforelse
        </ul>
    </x-section-card>
</div>

<div class="mt-6 flex flex-wrap gap-3">
    <a href="{{ route('admin.rapports.excel', ['debut' => $debut, 'fin' => $fin]) }}" class="btn-primary inline-flex items-center gap-2">
        <x-nav-icon name="download" class="h-4 w-4" /> Exporter Excel (CSV)
    </a>
    <a href="{{ route('admin.rapports.pdf', ['debut' => $debut, 'fin' => $fin]) }}" target="_blank" class="btn-secondary inline-flex items-center gap-2">
        <x-nav-icon name="download" class="h-4 w-4" /> Exporter PDF
    </a>
</div>
@endsection
