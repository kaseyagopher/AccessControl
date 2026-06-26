@extends('layouts.simple')

@section('title', 'Envoyer une lettre')

@section('content')
<x-page-header title="Envoyer une lettre" subtitle="Transmettre un document à la sécurité" icon="mail" color="violet" />

<div class="card max-w-xl">
    <form method="POST" action="{{ route('superviseur.lettres.store') }}" enctype="multipart/form-data" class="space-y-5">
        @csrf
        <x-form-field label="Objet" name="objet" :value="old('objet')" required full />
        <x-form-field label="Commentaire" name="commentaire" type="textarea" :value="old('commentaire')" full :rows="4" />
        <div>
            <label for="fichier" class="mb-1.5 block text-sm font-medium text-slate-700">Fichier (PDF, Word, image)</label>
            <input id="fichier" type="file" name="fichier" class="input-field @error('fichier') border-brand-400 @enderror">
            @error('fichier')
                <p class="mt-1 text-sm text-brand-700">{{ $message }}</p>
            @enderror
        </div>
        <button type="submit" class="btn-primary">Envoyer</button>
    </form>
</div>
@endsection
