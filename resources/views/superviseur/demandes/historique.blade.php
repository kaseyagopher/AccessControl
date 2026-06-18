@extends('layouts.simple')

@section('title', 'Historique')

@section('content')
<x-page-header title="Historique des visiteurs" subtitle="Toutes vos demandes passées" icon="clock" color="amber" />

<div class="space-y-4">
    @forelse ($demandes as $demande)
        <x-list-card icon="clock" color="amber">
            <h3 class="font-semibold text-slate-900">{{ $demande->visiteur->prenom }} {{ $demande->visiteur->nom }}</h3>
            <p class="text-sm text-slate-500">{{ $demande->visiteur->entreprise }}</p>
            <dl class="mt-3 grid gap-1 text-sm text-slate-600 sm:grid-cols-2">
                <div>Date prévue : {{ $demande->date_prevue->format('d/m/Y') }} {{ $demande->heure_prevue }}</div>
                <div>Créée le : {{ $demande->created_at->format('d/m/Y H:i') }}</div>
                @if ($demande->date_validation)<div>Validée/refusée : {{ $demande->date_validation->format('d/m/Y H:i') }}</div>@endif
                @if ($demande->heure_arrivee)<div class="text-emerald-600">Arrivée : {{ $demande->heure_arrivee->format('H:i') }}</div>@endif
                @if ($demande->heure_sortie)<div>Sortie : {{ $demande->heure_sortie->format('H:i') }}</div>@endif
            </dl>
            <x-slot:aside>
                <x-badge :status="$demande->statut" />
            </x-slot:aside>
        </x-list-card>
    @empty
        <x-empty-state icon="clock" color="amber" message="Aucun historique." />
    @endforelse
</div>
@endsection
