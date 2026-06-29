@extends('layouts.simple')

@section('title', 'Superviseur')

@section('content')
<x-page-header title="Tableau de bord" :subtitle="'Bienvenue, '.Auth::user()->name" icon="home" color="brand" />

<div class="mb-8 grid gap-4 sm:grid-cols-3">
    <x-stat-card label="Visiteurs attendus" :value="$stats['attendus']" icon="calendar-plus" color="sky" />
    <x-stat-card label="Visites validées" :value="$stats['validees']" icon="check-circle" color="emerald" />
    <x-stat-card label="Visites refusées" :value="$stats['refusees']" icon="x-circle" color="red" />
</div>

<div class="mb-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
    <x-dashboard-action
        :href="route('superviseur.demandes.create')"
        title="Formulaire VNF"
        description="Remplir et envoyer une visite"
        icon="calendar-plus"
        color="brand"
    />
    <x-dashboard-action
        :href="route('superviseur.demandes.index')"
        title="Statut VNF"
        description="Validé, en attente ou non validé"
        icon="clipboard"
        color="sky"
    />
    <x-dashboard-action
        :href="route('superviseur.archivages')"
        title="Archivages"
        description="Consulter l'historique filtré"
        icon="clock"
        color="amber"
    />
</div>


@endsection
