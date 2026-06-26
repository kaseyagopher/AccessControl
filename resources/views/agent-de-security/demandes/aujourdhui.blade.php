@extends('layouts.simple')

@section('title', 'Accès / sortie site')

@section('content')
<x-page-header title="Gérer infos accès et sortie site" :subtitle="now()->format('d/m/Y')" icon="calendar-plus" color="emerald" />

<div class="space-y-4">
    @forelse ($demandes as $demande)
        @php
            $visiteurs = $demande->visiteurs->isNotEmpty() ? $demande->visiteurs : collect([$demande->visiteur]);
            $principal = $visiteurs->first();
        @endphp
        <x-list-card icon="clock" color="emerald">
            <h3 class="font-semibold text-slate-900">{{ $principal->prenom }} {{ $principal->postnom }} {{ $principal->nom }}</h3>
            <p class="text-sm text-slate-500">{{ $principal->entreprise }} — Superviseur : {{ $demande->superviseur->name }}</p>
            @if ($demande->service)<p class="text-sm text-slate-500">{{ $demande->service->departement->nom ?? '' }} — {{ $demande->service->nom }}</p>@endif
            <p class="mt-1 text-sm text-slate-600">Heure prévue : {{ $demande->heure_prevue }}</p>
            @if ($demande->heure_arrivee)<p class="text-sm font-medium text-brand-700">Entrée : {{ $demande->heure_arrivee->format('H:i') }}</p>@endif
            @if ($demande->heure_sortie)<p class="text-sm text-slate-600">Sortie : {{ $demande->heure_sortie->format('H:i') }}</p>@endif
            <x-slot:aside>
                @if (!$demande->heure_arrivee)
                    <form method="POST" action="{{ route('agent.demandes.arrivee', $demande->id) }}">@csrf
                        <button type="submit" class="btn-primary inline-flex items-center gap-1 text-sm">
                            <x-nav-icon name="check-circle" class="h-4 w-4" /> Accès site
                        </button>
                    </form>
                @endif
                @if ($demande->heure_arrivee && !$demande->heure_sortie)
                    <form method="POST" action="{{ route('agent.demandes.sortie', $demande->id) }}">@csrf
                        <button type="submit" class="btn-secondary inline-flex items-center gap-1 text-sm">
                            <x-nav-icon name="clock" class="h-4 w-4" /> Sortie site
                        </button>
                    </form>
                @endif
                @if ($demande->heure_sortie)
                    <x-badge status="termine" />
                @endif
            </x-slot:aside>
        </x-list-card>
    @empty
        <x-empty-state icon="calendar-plus" color="emerald" message="Aucune visite prévue aujourd'hui." />
    @endforelse
</div>
@endsection
