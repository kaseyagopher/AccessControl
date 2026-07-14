@extends('layouts.simple')

@section('title', 'Superviseur')

@section('content')
<x-page-header title="Tableau de bord" :subtitle="'Bienvenue, '.Auth::user()->name" icon="home" color="brand" />

<div class="mb-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
    <x-stat-card label="Visiteurs attendus" :value="$stats['attendus']" icon="calendar-plus" color="sky" />
    <x-stat-card label="En attente sécurité" :value="$stats['en_attente']" icon="clipboard" color="amber" />
    <x-stat-card label="Validées" :value="$stats['validees']" icon="check-circle" color="emerald" />
    <x-stat-card label="Refusées" :value="$stats['refusees']" icon="x-circle" color="red" />
    <x-stat-card label="Terminées" :value="$stats['terminees']" icon="clock" color="charcoal" />
    <x-stat-card label="Expirées" :value="$stats['expirees']" icon="clock" color="orange" />
</div>

@if ($recentDemandes->count())
    <x-section-card title="Activité récente VNF" icon="clipboard" color="sky" class="mb-8">
        <div class="space-y-3">
            @foreach ($recentDemandes as $demande)
                @php
                    $visiteurs = $demande->visiteurs->isNotEmpty() ? $demande->visiteurs : collect([$demande->visiteur]);
                    $principal = $visiteurs->first();
                @endphp
                <div class="flex flex-col gap-2 rounded-xl border border-slate-100 bg-slate-50 p-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="font-medium text-slate-900">{{ $principal?->nomComplet() ?? 'Visiteur' }} — {{ $demande->date_prevue->format('d/m/Y') }}</p>
                        <p class="text-sm text-slate-500">{{ $visiteurs->count() }} visiteur(s) — {{ $demande->service?->nom ?? '—' }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <x-badge :status="$demande->statut" />
                        <a href="{{ route('superviseur.demandes.show', $demande->id) }}" class="btn-secondary text-xs">Voir</a>
                    </div>
                </div>
            @endforeach
        </div>
    </x-section-card>
@endif

<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
    <x-dashboard-action
        :href="route('superviseur.demandes.create')"
        title="Formulaire VNF"
        description="Remplir et envoyer une visite"
        icon="calendar-plus"
        color="brand"
    />
    <x-dashboard-action
        :href="route('superviseur.demandes.index')"
        title="Statut VNF"
        description="Validé, en attente ou non validé"
        icon="clipboard"
        color="sky"
    />
    <x-dashboard-action
        :href="route('superviseur.archivages')"
        title="Archivages"
        description="Consulter l'historique filtré"
        icon="clock"
        color="amber"
    />
</div>
@endsection
