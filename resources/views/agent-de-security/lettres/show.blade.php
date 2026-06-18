@extends('layouts.simple')

@section('title', 'Détail lettre')

@section('content')
<x-page-header :title="$lettre->objet" icon="mail" color="violet">
    <x-slot:actions>
        <x-badge :status="$lettre->statut" />
        <a href="{{ route('agent.lettres.index') }}" class="btn-secondary">Retour</a>
    </x-slot:actions>
</x-page-header>

<x-section-card title="Informations" icon="mail" color="violet" class="mb-6 max-w-2xl">
    <dl class="space-y-3 text-sm">
        <div><dt class="text-slate-500">Superviseur</dt><dd class="font-medium">{{ $lettre->superviseur->name }}</dd></div>
        <div><dt class="text-slate-500">Commentaire</dt><dd>{{ $lettre->commentaire ?? '—' }}</dd></div>
    </dl>
    @if ($lettre->fichier_path)
        <a href="{{ asset('storage/'.$lettre->fichier_path) }}" target="_blank" class="btn-secondary mt-4 inline-flex items-center gap-2 text-sm">
            <x-nav-icon name="download" class="h-4 w-4" /> Télécharger le fichier
        </a>
    @endif
</x-section-card>

@if (in_array($lettre->statut, ['envoyee', 'recue']))
    <div class="grid max-w-4xl gap-6 lg:grid-cols-2">
        <x-section-card title="Valider / Traiter" icon="check-circle" color="emerald">
            <form method="POST" action="{{ route('agent.lettres.valider', $lettre->id) }}" class="space-y-3">
                @csrf
                <textarea name="observation_securite" placeholder="Observation (optionnel)" rows="3" class="input-field @error('observation_securite') border-red-300 @enderror">{{ old('observation_securite') }}</textarea>
                @error('observation_securite')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
                <button type="submit" class="btn-primary w-full">Marquer comme traitée</button>
            </form>
        </x-section-card>
        <x-section-card title="Refuser" icon="x-circle" color="red">
            <form method="POST" action="{{ route('agent.lettres.refuser', $lettre->id) }}" class="space-y-3">
                @csrf
                <textarea name="observation_securite" placeholder="Motif du refus *" rows="3" required class="input-field @error('observation_securite') border-red-300 @enderror">{{ old('observation_securite') }}</textarea>
                @error('observation_securite')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
                <button type="submit" class="btn-danger w-full">Refuser</button>
            </form>
        </x-section-card>
    </div>
@endif
@endsection
