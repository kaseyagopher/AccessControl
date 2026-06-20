@extends('layouts.simple')

@section('title', 'Demandes reçues')

@section('content')
<x-page-header title="Demandes de visite" subtitle="Toutes les demandes à traiter" icon="inbox" color="amber" />

<x-section-card title="Rechercher" icon="search" color="brand" class="mb-6">
    <form method="GET" class="grid gap-3 sm:grid-cols-2 lg:grid-cols-5">
        <input type="text" name="nom" placeholder="Nom" value="{{ request('nom') }}" class="input-field">
        <input type="text" name="entreprise" placeholder="Entreprise" value="{{ request('entreprise') }}" class="input-field">
        <input type="date" name="date" value="{{ request('date') }}" class="input-field">
        <input type="text" name="superviseur" placeholder="Superviseur" value="{{ request('superviseur') }}" class="input-field">
        <button type="submit" class="btn-primary inline-flex items-center justify-center gap-2">
            <x-nav-icon name="search" class="h-4 w-4" /> Rechercher
        </button>
    </form>
</x-section-card>

<div class="space-y-4">
    @forelse ($demandes as $demande)
        @php
            $visiteurs = $demande->visiteurs->isNotEmpty() ? $demande->visiteurs : collect([$demande->visiteur]);
            $principal = $visiteurs->first();
        @endphp
        <x-list-card icon="clipboard" color="amber">
            <h3 class="font-semibold text-slate-900">{{ $principal->prenom }} {{ $principal->nom }}@if($visiteurs->count() > 1) <span class="text-sm font-normal text-slate-500">(+{{ $visiteurs->count() - 1 }})</span>@endif</h3>
            <p class="text-sm text-slate-500">Superviseur : {{ $demande->superviseur->name }}</p>
            <p class="text-sm text-slate-600">{{ $demande->date_prevue->format('d/m/Y') }}</p>
            <x-slot:aside>
                <x-badge :status="$demande->statut" />
                <a href="{{ route('agent.demandes.show', $demande->id) }}" class="btn-primary inline-flex items-center gap-1 text-sm">
                    Détails <x-nav-icon name="shield" class="h-4 w-4" />
                </a>
            </x-slot:aside>
        </x-list-card>
    @empty
        <x-empty-state icon="inbox" color="amber" message="Aucune demande." />
    @endforelse
</div>
@endsection
