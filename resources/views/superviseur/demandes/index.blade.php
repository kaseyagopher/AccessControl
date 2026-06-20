@extends('layouts.simple')

@section('title', 'Mes demandes')

@section('content')
<x-page-header title="Mes demandes de visite" icon="clipboard" color="sky">
    <x-slot:actions>
        <a href="{{ route('superviseur.demandes.create') }}" class="btn-primary inline-flex items-center gap-2">
            <x-nav-icon name="calendar-plus" class="h-4 w-4" /> Nouvelle demande
        </a>
    </x-slot:actions>
</x-page-header>

<x-section-card title="Rechercher" icon="search" color="brand" class="mb-6">
    <form method="GET" class="grid gap-3 sm:grid-cols-4">
        <input type="text" name="nom" placeholder="Nom" value="{{ request('nom') }}" class="input-field">
        <input type="text" name="entreprise" placeholder="Entreprise" value="{{ request('entreprise') }}" class="input-field">
        <input type="date" name="date" value="{{ request('date') }}" class="input-field">
        <button type="submit" class="btn-primary inline-flex items-center justify-center gap-2">
            <x-nav-icon name="search" class="h-4 w-4" /> Rechercher
        </button>
    </form>
</x-section-card>

<div class="space-y-4">
    @forelse ($demandes as $demande)
        @php
            $visiteurs = $demande->visiteurs->isNotEmpty() ? $demande->visiteurs : collect([$demande->visiteur]);
        @endphp
        <x-list-card icon="calendar-plus" color="sky">
            <h3 class="font-semibold text-slate-900">
                {{ $visiteurs->count() }} visiteur(s) — {{ $demande->date_prevue->format('d/m/Y') }} à {{ $demande->heure_prevue }}
            </h3>
            <ul class="mt-2 space-y-1">
                @foreach ($visiteurs as $v)
                    <li class="text-sm text-slate-600">{{ $v->prenom }} {{ $v->nom }} ({{ $v->entreprise }})</li>
                @endforeach
            </ul>
            <p class="mt-2 text-sm text-slate-600">{{ Str::limit($demande->motif, 80) }}</p>
            @if ($demande->commentaire_securite)
                <p class="mt-2 rounded-lg bg-amber-50 px-3 py-2 text-sm text-amber-800"><strong>Sécurité :</strong> {{ $demande->commentaire_securite }}</p>
            @endif
            <x-slot:aside>
                <x-badge :status="$demande->statut" />
            </x-slot:aside>
        </x-list-card>
    @empty
        <x-empty-state icon="clipboard" color="sky" message="Aucune demande trouvée." />
    @endforelse
</div>
@endsection
