@extends('layouts.simple')

@section('title', 'Détail demande')

@section('content')
<x-page-header :title="$demande->visiteur->prenom.' '.$demande->visiteur->nom" icon="clipboard" color="amber">
    <x-slot:actions>
        <x-badge :status="$demande->statut" />
        <a href="{{ route('agent.demandes.index') }}" class="btn-secondary">Retour</a>
    </x-slot:actions>
</x-page-header>

<div class="grid gap-6 lg:grid-cols-2">
    <x-section-card title="Visiteur" icon="user" color="sky">
        <dl class="space-y-2 text-sm">
            <div class="flex justify-between border-b border-slate-100 py-2"><dt class="text-slate-500">Nom</dt><dd>{{ $demande->visiteur->nom }}</dd></div>
            <div class="flex justify-between border-b border-slate-100 py-2"><dt class="text-slate-500">Prénom</dt><dd>{{ $demande->visiteur->prenom }}</dd></div>
            <div class="flex justify-between border-b border-slate-100 py-2"><dt class="text-slate-500">Téléphone</dt><dd>{{ $demande->visiteur->telephone }}</dd></div>
            <div class="flex justify-between border-b border-slate-100 py-2"><dt class="text-slate-500">Entreprise</dt><dd>{{ $demande->visiteur->entreprise }}</dd></div>
            <div class="flex justify-between py-2"><dt class="text-slate-500">Fonction</dt><dd>{{ $demande->visiteur->fonction ?? '—' }}</dd></div>
        </dl>
    </x-section-card>

    <x-section-card title="Visite" icon="calendar-plus" color="emerald">
        <dl class="space-y-2 text-sm">
            <div class="flex justify-between border-b border-slate-100 py-2"><dt class="text-slate-500">Superviseur</dt><dd>{{ $demande->superviseur->name }}</dd></div>
            <div class="flex justify-between border-b border-slate-100 py-2"><dt class="text-slate-500">Motif</dt><dd class="text-right max-w-[60%]">{{ $demande->motif }}</dd></div>
            <div class="flex justify-between border-b border-slate-100 py-2"><dt class="text-slate-500">Date</dt><dd>{{ $demande->date_prevue->format('d/m/Y') }} {{ $demande->heure_prevue }}</dd></div>
            <div class="flex justify-between py-2"><dt class="text-slate-500">Visiteurs</dt><dd>{{ $demande->nombre_visiteurs }}</dd></div>
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
        <x-section-card title="Valider" icon="check-circle" color="emerald" class="border-emerald-100">
            <form method="POST" action="{{ route('agent.demandes.valider', $demande->id) }}" class="space-y-3">
                @csrf
                <textarea name="commentaire_securite" placeholder="Commentaire (optionnel)" rows="3" class="input-field @error('commentaire_securite') border-red-300 @enderror">{{ old('commentaire_securite') }}</textarea>
                @error('commentaire_securite')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
                <button type="submit" class="btn-primary w-full bg-emerald-600 hover:bg-emerald-700">Valider la demande</button>
            </form>
        </x-section-card>
        <x-section-card title="Refuser" icon="x-circle" color="red" class="border-red-100">
            <form method="POST" action="{{ route('agent.demandes.refuser', $demande->id) }}" class="space-y-3">
                @csrf
                <textarea name="commentaire_securite" placeholder="Motif du refus *" rows="3" required class="input-field @error('commentaire_securite') border-red-300 @enderror">{{ old('commentaire_securite') }}</textarea>
                @error('commentaire_securite')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
                <button type="submit" class="btn-danger w-full">Refuser la demande</button>
            </form>
        </x-section-card>
    </div>
@endif
@endsection
