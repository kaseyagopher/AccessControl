@extends('layouts.simple')

@section('title', 'Détail VNF')

@section('content')
@php
    $visiteurs = $demande->visiteurs->isNotEmpty() ? $demande->visiteurs : collect([$demande->visiteur]);
@endphp

<x-page-header :title="'VNF — '.$visiteurs->count().' visiteur(s)'" icon="clipboard" color="sky">
    <x-slot:actions>
        <x-badge :status="$demande->statut" />
        @if ($demande->isEditableBySuperviseur())
            <a href="{{ route('superviseur.demandes.edit', $demande->id) }}" class="btn-secondary">Modifier</a>
        @endif
        @if ($demande->statut === 'brouillon')
            <form method="POST" action="{{ route('superviseur.demandes.envoyer', $demande->id) }}">
                @csrf
                <button type="submit" class="btn-primary">Envoyer</button>
            </form>
        @endif
        <a href="{{ route('superviseur.demandes.index') }}" class="btn-secondary">Retour</a>
    </x-slot:actions>
</x-page-header>

<div class="grid gap-6 lg:grid-cols-2">
    <x-section-card title="Visiteurs" icon="users" color="sky">
        <div class="space-y-3">
            @foreach ($visiteurs as $index => $v)
                <div class="rounded-xl border border-slate-100 bg-slate-50 p-4 text-sm">
                    <p class="mb-2 font-semibold text-slate-900">Visiteur {{ $index + 1 }}</p>
                    <dl class="space-y-1">
                        <div class="flex justify-between"><dt class="text-slate-500">Nom</dt><dd>{{ $v->nom }}</dd></div>
                        @if ($v->postnom)<div class="flex justify-between"><dt class="text-slate-500">Postnom</dt><dd>{{ $v->postnom }}</dd></div>@endif
                        <div class="flex justify-between"><dt class="text-slate-500">Prénom</dt><dd>{{ $v->prenom }}</dd></div>
                        @if ($v->genre)<div class="flex justify-between"><dt class="text-slate-500">Genre</dt><dd>{{ $v->genre }}</dd></div>@endif
                        <div class="flex justify-between"><dt class="text-slate-500">Entreprise</dt><dd>{{ $v->entreprise }}</dd></div>
                    </dl>
                </div>
            @endforeach
        </div>
    </x-section-card>

    <x-section-card title="Informations VNF" icon="calendar-plus" color="emerald">
        <dl class="space-y-2 text-sm">
            @if ($demande->service)
                <div class="flex justify-between border-b border-slate-100 py-2"><dt class="text-slate-500">Département</dt><dd>{{ $demande->service->departement->nom ?? '—' }}</dd></div>
                <div class="flex justify-between border-b border-slate-100 py-2"><dt class="text-slate-500">Service</dt><dd>{{ $demande->service->nom }}</dd></div>
            @endif
            <div class="flex justify-between border-b border-slate-100 py-2"><dt class="text-slate-500">Motif</dt><dd class="max-w-[60%] text-right">{{ $demande->motif }}</dd></div>
            <div class="flex justify-between border-b border-slate-100 py-2"><dt class="text-slate-500">Date</dt><dd>{{ $demande->date_prevue->format('d/m/Y') }} {{ $demande->heure_prevue }}</dd></div>
            @if ($demande->heure_arrivee)<div class="flex justify-between border-b border-slate-100 py-2"><dt class="text-slate-500">Heure d'entrée</dt><dd>{{ $demande->heure_arrivee->format('H:i') }}</dd></div>@endif
            @if ($demande->heure_sortie)<div class="flex justify-between border-b border-slate-100 py-2"><dt class="text-slate-500">Heure de sortie</dt><dd>{{ $demande->heure_sortie->format('H:i') }}</dd></div>@endif
        </dl>
        @if ($demande->commentaire_securite)
            <p class="mt-4 rounded-lg bg-amber-50 px-3 py-2 text-sm text-amber-900"><strong>Sécurité :</strong> {{ $demande->commentaire_securite }}</p>
        @endif
    </x-section-card>
</div>

@if ($demande->heure_arrivee)
    <x-section-card title="Observation accès site" icon="clipboard" color="brand" class="mt-6">
        @if ($demande->observation_acces)
            <p class="mb-4 text-sm text-slate-700">{{ $demande->observation_acces }}</p>
        @endif
        <form method="POST" action="{{ route('superviseur.demandes.observation-acces', $demande->id) }}" class="space-y-3">
            @csrf
            <textarea name="observation_acces" rows="3" class="input-field" placeholder="Saisir une observation d'accès..." required>{{ old('observation_acces', $demande->observation_acces) }}</textarea>
            <button type="submit" class="btn-primary text-sm">{{ $demande->observation_acces ? 'Mettre à jour' : 'Enregistrer' }}</button>
        </form>
    </x-section-card>
@endif

@if ($demande->heure_sortie)
    <x-section-card title="Observation sortie site" icon="clock" color="amber" class="mt-6">
        @if ($demande->observation_sortie)
            <p class="mb-4 text-sm text-slate-700">{{ $demande->observation_sortie }}</p>
        @endif
        <form method="POST" action="{{ route('superviseur.demandes.observation-sortie', $demande->id) }}" class="space-y-3">
            @csrf
            <textarea name="observation_sortie" rows="3" class="input-field" placeholder="Saisir une observation de sortie..." required>{{ old('observation_sortie', $demande->observation_sortie) }}</textarea>
            <button type="submit" class="btn-primary text-sm">{{ $demande->observation_sortie ? 'Mettre à jour' : 'Enregistrer' }}</button>
        </form>
    </x-section-card>
@endif
@endsection
