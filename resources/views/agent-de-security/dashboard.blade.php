@extends('layouts.simple')

@section('title', 'Agent de sécurité')

@section('content')
<x-page-header title="Tableau de bord" :subtitle="'Bienvenue, '.Auth::user()->name" icon="home" color="brand" />

<div class="mb-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    <x-stat-card label="Visiteurs aujourd'hui" :value="$stats['aujourdhui']" icon="calendar-plus" color="sky" />
    <x-stat-card label="Entrées enregistrées" :value="$stats['entrees_jour']" icon="check-circle" color="emerald" />
    <x-stat-card label="Sorties enregistrées" :value="$stats['sorties_jour']" icon="clock" color="violet" />
    <x-stat-card label="Demandes à traiter" :value="$stats['a_traiter']" icon="clipboard" color="amber" />
</div>

<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
    <x-dashboard-action
        :href="route('agent.demandes.index')"
        title="Consulter VNF"
        description="Valider ou refuser les visites"
        icon="clipboard"
        color="brand"
    />
    <x-dashboard-action
        :href="route('agent.visites.aujourdhui')"
        title="Accès / sortie site"
        description="Enregistrer entrées et sorties"
        icon="clock"
        color="emerald"
    />
    <x-dashboard-action
        :href="route('agent.archivages')"
        title="Archivages"
        description="Historique filtré des visites"
        icon="clock"
        color="amber"
    />
</div>
@endsection
