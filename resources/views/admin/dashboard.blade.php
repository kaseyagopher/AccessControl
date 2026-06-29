@extends('layouts.simple')

@section('title', 'Admin')

@section('content')
<x-page-header title="Tableau de bord" :subtitle="'Bienvenue, '.Auth::user()->name" icon="home" color="brand" />

<div class="mb-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
    <x-stat-card label="Superviseurs" :value="$stats['superviseurs']" icon="users" color="violet" />
    <x-stat-card label="Agents de sécurité" :value="$stats['agents']" icon="shield" color="cyan" />
    <x-stat-card label="Visiteurs du jour" :value="$stats['visiteurs_jour']" icon="calendar-plus" color="sky" />
    <x-stat-card label="En attente" :value="$stats['en_attente']" icon="clipboard" color="amber" />
    <x-stat-card label="Validées" :value="$stats['validees']" icon="check-circle" color="emerald" />
</div>

<div class="grid gap-4 sm:grid-cols-2">
    <x-dashboard-action
        :href="route('admin.enregistrement')"
        title="Créer un utilisateur"
        description="Superviseur ou agent de sécurité"
        icon="user-plus"
        color="brand"
    />
    <x-dashboard-action
        :href="route('admin.users')"
        title="Liste des utilisateurs"
        description="Gérer, modifier, désactiver"
        icon="users"
        color="violet"
    />
    <x-dashboard-action
        :href="route('admin.settings.edit')"
        title="Mon profil"
        description="Paramètres du compte admin"
        icon="cog"
        color="sky"
    />
    <x-dashboard-action
        :href="route('admin.rapports')"
        title="Rapports"
        description="Statistiques et exports"
        icon="chart"
        color="amber"
    />
    <x-dashboard-action
        :href="route('admin.archivages')"
        title="Archivages"
        description="Historique de toutes les visites"
        icon="clock"
        color="emerald"
    />
</div>
@endsection
