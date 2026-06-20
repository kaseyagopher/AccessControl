@php
    $role = Auth::user()->role;
    $links = match($role) {
        'admin' => [
            ['route' => 'admin.dashboard', 'label' => 'Tableau de bord', 'icon' => 'home', 'prefix' => 'admin', 'exact' => true],
            ['route' => 'admin.enregistrement', 'label' => 'Créer utilisateur', 'icon' => 'user-plus', 'prefix' => 'admin/enregistrement'],
            ['route' => 'admin.users', 'label' => 'Utilisateurs', 'icon' => 'users', 'prefix' => 'admin/users'],
            ['route' => 'admin.rapports', 'label' => 'Rapports', 'icon' => 'chart', 'prefix' => 'admin/rapports'],
            ['route' => 'admin.archivages', 'label' => 'Archivages', 'icon' => 'clock', 'prefix' => 'admin/archivages'],
            ['route' => 'admin.settings.edit', 'label' => 'Mon profil', 'icon' => 'user', 'prefix' => 'admin/settings'],
        ],
        'superviseur' => [
            ['route' => 'superviseur.dashboard', 'label' => 'Tableau de bord', 'icon' => 'home', 'prefix' => 'superviseur', 'exact' => true],
            ['route' => 'superviseur.demandes.create', 'label' => 'Nouvelle visite', 'icon' => 'calendar-plus', 'prefix' => 'superviseur/demandes/create'],
            ['route' => 'superviseur.demandes.index', 'label' => 'Mes demandes', 'icon' => 'clipboard', 'prefix' => 'superviseur/demandes', 'exclude' => 'superviseur/demandes/create'],
            ['route' => 'superviseur.archivages', 'label' => 'Archivages', 'icon' => 'clock', 'prefix' => 'superviseur/archivages'],
            ['route' => 'superviseur.settings.edit', 'label' => 'Mon profil', 'icon' => 'user', 'prefix' => 'superviseur/settings'],
        ],
        'agent-de-security' => [
            ['route' => 'agent-de-security.dashboard', 'label' => 'Tableau de bord', 'icon' => 'home', 'prefix' => 'agent-de-security', 'exact' => true],
            ['route' => 'agent.demandes.index', 'label' => 'Demandes', 'icon' => 'inbox', 'prefix' => 'agent-de-security/demandes'],
            ['route' => 'agent.visites.aujourdhui', 'label' => 'Visites du jour', 'icon' => 'calendar-plus', 'prefix' => 'agent-de-security/visites-aujourdhui'],
            ['route' => 'agent.archivages', 'label' => 'Archivages', 'icon' => 'clock', 'prefix' => 'agent-de-security/archivages'],
            ['route' => 'agent.lettres.index', 'label' => 'Lettres', 'icon' => 'mail', 'prefix' => 'agent-de-security/lettres'],
        ],
        default => [],
    };

    $isActive = function ($link) {
        if (request()->routeIs($link['route'])) {
            return true;
        }
        if (!empty($link['exact']) && request()->is($link['prefix'])) {
            return true;
        }
        if (!empty($link['exact'])) {
            return false;
        }
        if (!empty($link['exclude']) && request()->is($link['exclude'])) {
            return false;
        }
        return request()->is($link['prefix'].'*');
    };
@endphp

<nav class="flex flex-1 flex-col gap-1 px-3 py-2">
    @foreach($links as $link)
        @php $active = $isActive($link); @endphp
        <a href="{{ route($link['route']) }}"
           class="sidebar-link {{ $active ? 'sidebar-link-active' : '' }}">
            <x-nav-icon :name="$link['icon']" />
            <span class="flex-1">{{ $link['label'] }}</span>
            @if($active)
                <svg class="h-4 w-4 text-brand-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
            @endif
        </a>
    @endforeach
</nav>
