@extends('layouts.simple')

@section('title', 'Nouvelle demande')

@section('content')
<x-page-header title="Pré-enregistrement visiteur" subtitle="Envoyez une demande à la sécurité" icon="calendar-plus" color="emerald">
    <x-slot:actions>
        <a href="{{ route('superviseur.dashboard') }}" class="btn-secondary">Retour</a>
    </x-slot:actions>
</x-page-header>

<div class="card max-w-2xl">
    <form method="POST" action="{{ route('superviseur.demandes.store') }}" enctype="multipart/form-data" class="space-y-5">
        @csrf
        <div class="grid gap-5 sm:grid-cols-2">
            <x-form-field label="Nom" name="nom" :value="old('nom')" required />
            <x-form-field label="Prénom" name="prenom" :value="old('prenom')" required />
            <x-form-field label="Téléphone" name="telephone" :value="old('telephone')" required />
            <x-form-field label="Entreprise" name="entreprise" :value="old('entreprise')" required />
            <x-form-field label="Fonction / Poste" name="fonction" :value="old('fonction')" full />
            <x-form-field label="Motif" name="motif" type="textarea" :value="old('motif')" required full :rows="3" />
            <x-form-field label="Date prévue" name="date_prevue" type="date" :value="old('date_prevue')" required />
            <x-form-field label="Heure prévue" name="heure_prevue" type="time" :value="old('heure_prevue')" required />
            <x-form-field label="Nombre de visiteurs" name="nombre_visiteurs" type="number" :value="old('nombre_visiteurs', 1)" required />
            <div>
                <label for="document" class="mb-1.5 block text-sm font-medium text-slate-700">Document joint</label>
                <input id="document" type="file" name="document" class="input-field file:mr-3 file:rounded-lg file:border-0 file:bg-brand-50 file:px-3 file:py-1 file:text-sm file:font-medium file:text-brand-700 @error('document') border-red-300 @enderror">
                @error('document')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <button type="submit" class="btn-primary">Envoyer à la sécurité</button>
    </form>
</div>
@endsection
