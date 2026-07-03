@extends('layouts.simple')

@section('title', 'Nouvelle VNF')

@section('content')
<x-page-header title="Remplir formulaire VNF" subtitle="Enregistrer ou envoyer une visite" icon="calendar-plus" color="emerald">
    <x-slot:actions>
        <a href="{{ route('superviseur.dashboard') }}" class="btn-secondary">Retour</a>
    </x-slot:actions>
</x-page-header>

<div class="card max-w-3xl">
    <form id="demande-form" method="POST" action="{{ route('superviseur.demandes.store') }}" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @include('superviseur.demandes.partials.vnf-form', compact('departements', 'services', 'entreprises'))

        <div class="flex flex-wrap gap-3">
            <button type="submit" name="action" value="enregistrer" class="btn-secondary">Enregistrer</button>
            <button type="submit" name="action" value="envoyer" class="btn-primary">Envoyer à la sécurité</button>
        </div>
    </form>
</div>
@endsection
