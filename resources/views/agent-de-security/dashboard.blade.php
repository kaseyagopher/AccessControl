@extends('layouts.simple')

@section('title', 'Agent de sécurité')

@section('content')
<x-page-header title="Tableau de bord" :subtitle="'Bienvenue, '.Auth::user()->name" icon="home" color="brand" />

<div class="mb-8 grid gap-4 sm:grid-cols-3">
    <x-stat-card label="Visiteurs aujourd'hui" :value="$stats['aujourdhui']" icon="clock" color="sky" />
    <x-stat-card label="Demandes à traiter" :value="$stats['a_traiter']" icon="clipboard" color="amber" />
    <x-stat-card label="Lettres à traiter" :value="$stats['lettres']" icon="inbox" color="violet" />
</div>

<div class="mb-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
    <x-dashboard-action
        :href="route('agent.demandes.index')"
        title="Demandes reçues"
        description="Valider ou refuser les visites"
        icon="clipboard"
        color="brand"
    />
    <x-dashboard-action
        :href="route('agent.visites.aujourdhui')"
        title="Visites du jour"
        description="Enregistrer arrivées et sorties"
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
    <x-dashboard-action
        :href="route('agent.lettres.index')"
        title="Lettres reçues"
        description="Traiter les correspondances"
        icon="mail"
        color="violet"
    />
</div>

@if ($notifications->count())
    <div class="card">
        <div class="mb-4 flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                <x-nav-icon name="bell" class="h-5 w-5" />
            </div>
            <h2 class="text-lg font-semibold text-slate-900">Notifications</h2>
        </div>
        <div class="space-y-3">
            @foreach ($notifications as $notif)
                <div class="flex flex-col gap-2 rounded-xl border border-slate-100 bg-slate-50 p-4 sm:flex-row sm:items-center sm:justify-between {{ !$notif->lu ? 'border-l-4 border-l-amber-500' : '' }}">
                    <p class="text-sm text-slate-700">
                        @if (!$notif->lu)<span class="mr-2 font-semibold text-amber-600">Nouveau</span>@endif
                        {{ $notif->message }}
                    </p>
                    @if (!$notif->lu)
                        <form method="POST" action="{{ route('agent.notifications.lu', $notif->id) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn-secondary text-xs">Marquer lu</button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endif
@endsection
