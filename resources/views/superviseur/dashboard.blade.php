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

@if ($notifications->count())
    <div class="card">
        <div class="mb-4 flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-50 text-brand-600">
                <x-nav-icon name="bell" class="h-5 w-5" />
            </div>
            <h2 class="text-lg font-semibold text-slate-900">Notifications</h2>
        </div>
        <div class="space-y-3">
            @foreach ($notifications as $notif)
                <div class="flex flex-col gap-2 rounded-xl border border-slate-100 bg-slate-50 p-4 sm:flex-row sm:items-center sm:justify-between {{ !$notif->lu ? 'border-l-4 border-l-brand-600' : '' }}">
                    <p class="text-sm text-slate-700">
                        @if (!$notif->lu)<span class="mr-2 font-semibold text-brand-600">Nouveau</span>@endif
                        {{ $notif->message }}
                    </p>
                    @if (!$notif->lu)
                        <form method="POST" action="{{ route('superviseur.notifications.lu', $notif->id) }}">
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
