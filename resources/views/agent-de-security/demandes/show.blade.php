@extends('layouts.simple')

@section('title', 'Détail VNF')

@section('content')
@php
    $visiteurs = $demande->visiteurs->isNotEmpty() ? $demande->visiteurs : collect([$demande->visiteur]);
@endphp

<x-page-header :title="'Gérer infos VNF — '.$visiteurs->count().' personne(s)'" icon="clipboard" color="amber">
    <x-slot:actions>
        <x-badge :status="$demande->statut" />
        <a href="{{ route('agent.demandes.index') }}" class="btn-secondary">Retour</a>
    </x-slot:actions>
</x-page-header>

<div class="grid gap-6 lg:grid-cols-2">
    <x-section-card title="Visiteurs ({{ $visiteurs->count() }})" icon="users" color="sky">
        <div class="space-y-3">
            @foreach ($visiteurs as $index => $v)
                <div class="rounded-xl border border-slate-100 bg-slate-50 p-4 text-sm">
                    <p class="mb-2 font-semibold text-slate-900">Visiteur {{ $index + 1 }}</p>
                    <dl class="space-y-1">
                        <div class="flex justify-between"><dt class="text-slate-500">Nom</dt><dd>{{ $v->nom }}</dd></div>
                        @if ($v->postnom)<div class="flex justify-between"><dt class="text-slate-500">Postnom</dt><dd>{{ $v->postnom }}</dd></div>@endif
                        <div class="flex justify-between"><dt class="text-slate-500">Prénom</dt><dd>{{ $v->prenom }}</dd></div>
                        @if ($v->genre)<div class="flex justify-between"><dt class="text-slate-500">Genre</dt><dd>{{ $v->genre }}</dd></div>@endif
                        <div class="flex justify-between"><dt class="text-slate-500">Téléphone</dt><dd>{{ $v->telephone }}</dd></div>
                        <div class="flex justify-between"><dt class="text-slate-500">Entreprise</dt><dd>{{ $v->entreprise }}</dd></div>
                        <div class="flex justify-between"><dt class="text-slate-500">Fonction</dt><dd>{{ $v->fonction ?? '—' }}</dd></div>
                    </dl>
                </div>
            @endforeach
        </div>
    </x-section-card>

    <x-section-card title="Informations VNF" icon="calendar-plus" color="emerald">
        <dl class="space-y-2 text-sm">
            <div class="flex justify-between border-b border-slate-100 py-2"><dt class="text-slate-500">Superviseur</dt><dd>{{ $demande->superviseur->name }}</dd></div>
            @if ($demande->service)
                <div class="flex justify-between border-b border-slate-100 py-2"><dt class="text-slate-500">Département</dt><dd>{{ $demande->service->departement->nom ?? '—' }}</dd></div>
                <div class="flex justify-between border-b border-slate-100 py-2"><dt class="text-slate-500">Service</dt><dd>{{ $demande->service->nom }}</dd></div>
            @endif
            <div class="flex justify-between border-b border-slate-100 py-2"><dt class="text-slate-500">Motif</dt><dd class="text-right max-w-[60%]">{{ $demande->motif }}</dd></div>
            <div class="flex justify-between border-b border-slate-100 py-2"><dt class="text-slate-500">Date</dt><dd>{{ $demande->date_prevue->format('d/m/Y') }} {{ $demande->heure_prevue }}</dd></div>
            <div class="flex justify-between py-2"><dt class="text-slate-500">Nombre de visiteurs</dt><dd>{{ $demande->nombre_visiteurs }}</dd></div>
        </dl>
        @if ($demande->document_path)
            <a href="{{ asset('storage/'.$demande->document_path) }}" target="_blank" class="btn-secondary mt-4 inline-flex items-center gap-2 text-sm">
                <x-nav-icon name="download" class="h-4 w-4" /> Télécharger le document
            </a>
        @endif
    </x-section-card>
</div>

@if (in_array($demande->statut, ['en_attente', 'recu']))
    <div class="mt-6 grid gap-6 lg:grid-cols-2">
        <x-section-card title="Valider" icon="check-circle" color="brand" class="border-brand-100">
            <form method="POST" action="{{ route('agent.demandes.valider', $demande->id) }}" class="space-y-3">
                @csrf
                <textarea name="commentaire_securite" placeholder="Commentaire (optionnel)" rows="3" class="input-field @error('commentaire_securite') border-brand-400 @enderror">{{ old('commentaire_securite') }}</textarea>
                @error('commentaire_securite')
                    <p class="text-sm text-brand-700">{{ $message }}</p>
                @enderror
                <button type="submit" class="btn-primary w-full">Valider la demande</button>
            </form>
        </x-section-card>
        <x-section-card title="Refuser" icon="x-circle" color="red" class="border-brand-200">
            <form method="POST" action="{{ route('agent.demandes.refuser', $demande->id) }}" class="space-y-3">
                @csrf
                <textarea name="commentaire_securite" placeholder="Motif du refus *" rows="3" required class="input-field @error('commentaire_securite') border-brand-400 @enderror">{{ old('commentaire_securite') }}</textarea>
                @error('commentaire_securite')
                    <p class="text-sm text-brand-700">{{ $message }}</p>
                @enderror
                <button type="submit" class="btn-danger w-full">Refuser la demande</button>
            </form>
        </x-section-card>
    </div>
@endif
@endsection
