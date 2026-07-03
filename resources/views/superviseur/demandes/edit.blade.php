@extends('layouts.simple')

@section('title', 'Modifier VNF')

@section('content')
<x-page-header title="Mettre à jour infos VNF" subtitle="Modifier avant envoi ou validation" icon="pencil" color="amber">
    <x-slot:actions>
        <a href="{{ route('superviseur.demandes.show', $demande->id) }}" class="btn-secondary">Retour</a>
    </x-slot:actions>
</x-page-header>

<div class="card max-w-3xl">
    <form method="POST" action="{{ route('superviseur.demandes.update', $demande->id) }}" enctype="multipart/form-data" class="space-y-8">
        @csrf @method('PUT')
        @include('superviseur.demandes.partials.vnf-form', ['demande' => $demande, 'departements' => $departements, 'services' => $services, 'entreprises' => $entreprises])

        <div class="flex flex-wrap gap-3">
            <button type="submit" name="action" value="enregistrer" class="btn-secondary">Enregistrer</button>
            <button type="submit" name="action" value="envoyer" class="btn-primary">Envoyer à la sécurité</button>
        </div>
    </form>
</div>
@endsection
