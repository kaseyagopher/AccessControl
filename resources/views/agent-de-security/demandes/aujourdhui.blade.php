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
        <x-list-card icon="clock" :color="$demande->estVisiteAVenir() ? 'amber' : 'emerald'">
            <div class="flex flex-wrap items-center gap-2">
                <h3 class="font-semibold text-slate-900">{{ $principal->prenom }} {{ $principal->postnom }} {{ $principal->nom }}</h3>
                <x-badge :status="$demande->statut" />
                @if ($demande->estVisiteAVenir())
                    <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-800">À venir</span>
                @endif
            </div>
            <p class="text-sm text-slate-500">{{ $principal->entreprise }} — Superviseur : {{ $demande->superviseur->name }}</p>
            @if ($demande->service)
                <p class="text-sm text-slate-500">{{ $demande->service->departement->nom ?? '' }} — {{ $demande->service->nom }}</p>
            @endif
            <p class="mt-1 text-sm text-slate-600">
                Date prévue : {{ $demande->date_prevue->format('d/m/Y') }} à {{ $demande->heure_prevue }}
            </p>
            <div class="mt-2 flex flex-wrap gap-3 text-sm">
                <span class="{{ $demande->heure_arrivee ? 'font-medium text-emerald-700' : 'text-slate-400' }}">
                    Entrée : {{ $demande->heure_arrivee ? $demande->heure_arrivee->format('d/m/Y H:i') : '—' }}
                </span>
                <span class="{{ $demande->heure_sortie ? 'font-medium text-slate-700' : 'text-slate-400' }}">
                    Sortie : {{ $demande->heure_sortie ? $demande->heure_sortie->format('d/m/Y H:i') : '—' }}
                </span>
            </div>
            <x-slot:aside>
                <div class="flex flex-col gap-2 sm:items-end">
                    @if ($demande->peutEnregistrerAcces())
                        <form method="POST" action="{{ route('agent.demandes.arrivee', $demande->id) }}">@csrf
                            <button type="submit" class="btn-success inline-flex w-full items-center justify-center gap-1 text-sm sm:w-auto">
                                <x-nav-icon name="check-circle" class="h-4 w-4" /> Valider l'entrée
                            </button>
                        </form>
                    @elseif ($demande->estVisiteAVenir())
                        <span class="rounded-lg bg-amber-50 px-3 py-2 text-xs font-medium text-amber-800">
                            Validation possible le {{ $demande->date_prevue->format('d/m/Y') }}
                        </span>
                    @endif
                    @if ($demande->peutEnregistrerSortie())
                        <form method="POST" action="{{ route('agent.demandes.sortie', $demande->id) }}">@csrf
                            <button type="submit" class="btn-secondary inline-flex w-full items-center justify-center gap-1 text-sm sm:w-auto">
                                <x-nav-icon name="clock" class="h-4 w-4" /> Valider la sortie
                            </button>
                        </form>
                    @endif
                    @if ($demande->heure_sortie)
                        <x-badge status="termine" />
                    @endif
                </div>
            </x-slot:aside>
        </x-list-card>
    @empty
        <x-empty-state icon="calendar-plus" color="emerald" message="Aucune visite validée pour le moment." />
    @endforelse
</div>
@endsection
