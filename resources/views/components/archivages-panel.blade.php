@props([
    'filterRoute',
    'demandes',
    'annees',
    'showSuperviseur' => false,
    'detailRoute' => null,
])

<x-section-card title="Filtres de recherche" icon="search" color="brand" class="mb-6">
    <form method="GET" action="{{ $filterRoute }}" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div>
            <label class="mb-1.5 block text-sm font-medium text-slate-700">Nom</label>
            <input type="text" name="nom" value="{{ request('nom') }}" placeholder="Nom du visiteur" class="input-field">
        </div>
        <div>
            <label class="mb-1.5 block text-sm font-medium text-slate-700">Prénom</label>
            <input type="text" name="prenom" value="{{ request('prenom') }}" placeholder="Prénom" class="input-field">
        </div>
        <div>
            <label class="mb-1.5 block text-sm font-medium text-slate-700">Entreprise</label>
            <input type="text" name="entreprise" value="{{ request('entreprise') }}" placeholder="Entreprise" class="input-field">
        </div>
        @if ($showSuperviseur)
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">Superviseur</label>
                <input type="text" name="superviseur" value="{{ request('superviseur') }}" placeholder="Nom du superviseur" class="input-field">
            </div>
        @endif
        <div>
            <label class="mb-1.5 block text-sm font-medium text-slate-700">Année</label>
            <select name="annee" class="input-field">
                <option value="">Toutes les années</option>
                @foreach ($annees as $annee)
                    <option value="{{ $annee }}" @selected(request('annee') == $annee)>{{ $annee }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1.5 block text-sm font-medium text-slate-700">Date début</label>
            <input type="date" name="date_debut" value="{{ request('date_debut') }}" class="input-field">
        </div>
        <div>
            <label class="mb-1.5 block text-sm font-medium text-slate-700">Date fin</label>
            <input type="date" name="date_fin" value="{{ request('date_fin') }}" class="input-field">
        </div>
        <div>
            <label class="mb-1.5 block text-sm font-medium text-slate-700">Statut</label>
            <select name="statut" class="input-field">
                <option value="">Tous les statuts</option>
                @foreach (['brouillon', 'en_attente', 'recu', 'valide', 'refuse', 'termine'] as $s)
                    <option value="{{ $s }}" @selected(request('statut') === $s)>{{ str_replace('-', ' ', $s) }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end gap-2 sm:col-span-2 lg:col-span-1">
            <button type="submit" class="btn-primary inline-flex flex-1 items-center justify-center gap-2">
                <x-nav-icon name="search" class="h-4 w-4" /> Filtrer
            </button>
            <a href="{{ $filterRoute }}" class="btn-secondary">Réinitialiser</a>
        </div>
    </form>
</x-section-card>

<div class="space-y-4">
    @forelse ($demandes as $demande)
        @php
            $visiteurs = $demande->visiteurs->isNotEmpty() ? $demande->visiteurs : collect([$demande->visiteur]);
        @endphp
        <x-list-card icon="clock" color="amber">
            <div class="flex flex-wrap items-center gap-2">
                <h3 class="font-semibold text-slate-900">
                    {{ $visiteurs->count() }} visiteur(s) — {{ $demande->date_prevue->format('d/m/Y') }} à {{ $demande->heure_prevue }}
                </h3>
                <x-badge :status="$demande->statut" />
            </div>
            @if ($showSuperviseur && $demande->superviseur)
                <p class="mt-1 text-sm text-slate-500">Superviseur : {{ $demande->superviseur->name }}</p>
            @endif
            @if ($demande->service)
                <p class="mt-1 text-sm text-slate-500">{{ $demande->service->departement->nom ?? '' }} — {{ $demande->service->nom }}</p>
            @endif
            <ul class="mt-3 space-y-2">
                @foreach ($visiteurs as $v)
                    <li class="rounded-lg bg-slate-50 px-3 py-2 text-sm text-slate-700">
                        <span class="font-medium">{{ $v->prenom }} {{ $v->nom }}</span>
                        <span class="text-slate-500"> — {{ $v->entreprise }}</span>
                    </li>
                @endforeach
            </ul>
            <dl class="mt-3 grid gap-1 text-sm text-slate-600 sm:grid-cols-2">
                <div>Créée le : {{ $demande->created_at->format('d/m/Y H:i') }}</div>
                @if ($demande->date_validation)<div>Traitée le : {{ $demande->date_validation->format('d/m/Y H:i') }}</div>@endif
                @if ($demande->heure_arrivee)<div class="text-brand-700">Arrivée : {{ $demande->heure_arrivee->format('H:i') }}</div>@endif
                @if ($demande->heure_sortie)<div>Sortie : {{ $demande->heure_sortie->format('H:i') }}</div>@endif
            </dl>
            @if ($detailRoute)
                <x-slot:aside>
                    <a href="{{ route($detailRoute, $demande->id) }}" class="btn-primary text-sm">Détails</a>
                </x-slot:aside>
            @endif
        </x-list-card>
    @empty
        <x-empty-state icon="clock" color="amber" message="Aucun archivage trouvé pour ces critères." />
    @endforelse
</div>
