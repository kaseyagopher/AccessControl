@php
    $role = Auth::user()->role;
    $links = match($role) {
        'admin' => [
            ['route' => 'admin.dashboard', 'label' => 'Tableau de bord', 'icon' => 'home', 'prefix' => 'admin', 'exact' => true],
            ['route' => 'admin.enregistrement', 'label' => 'Créer utilisateur', 'icon' => 'user-plus', 'prefix' => 'admin/enregistrement'],
            ['route' => 'admin.users', 'label' => 'Utilisateurs', 'icon' => 'users', 'prefix' => 'admin/users'],
            ['route' => 'admin.archivages', 'label' => 'Archivages', 'icon' => 'clock', 'prefix' => 'admin/archivages'],
            ['route' => 'admin.settings.edit', 'label' => 'Mon profil', 'icon' => 'user', 'prefix' => 'admin/settings'],
        ],
        'superviseur' => [
            ['route' => 'superviseur.dashboard', 'label' => 'Tableau de bord', 'icon' => 'home', 'prefix' => 'superviseur', 'exact' => true],
            ['route' => 'superviseur.demandes.create', 'label' => 'Formulaire VNF', 'icon' => 'calendar-plus', 'prefix' => 'superviseur/demandes/create'],
            ['route' => 'superviseur.demandes.index', 'label' => 'Statut VNF', 'icon' => 'clipboard', 'prefix' => 'superviseur/demandes', 'exclude' => 'superviseur/demandes/create'],
            ['route' => 'superviseur.archivages', 'label' => 'Archivages', 'icon' => 'clock', 'prefix' => 'superviseur/archivages'],
            ['route' => 'superviseur.notifications.index', 'label' => 'Notifications', 'icon' => 'bell', 'prefix' => 'superviseur/notifications', 'badge' => true],
        ],
        'agent-de-security' => [
            ['route' => 'agent-de-security.dashboard', 'label' => 'Tableau de bord', 'icon' => 'home', 'prefix' => 'agent-de-security', 'exact' => true],
            ['route' => 'agent.demandes.index', 'label' => 'Consulter VNF', 'icon' => 'inbox', 'prefix' => 'agent-de-security/demandes'],
            ['route' => 'agent.visites.aujourdhui', 'label' => 'Accès / sortie site', 'icon' => 'calendar-plus', 'prefix' => 'agent-de-security/visites-aujourdhui'],
            ['route' => 'agent.archivages', 'label' => 'Archivages', 'icon' => 'clock', 'prefix' => 'agent-de-security/archivages'],
            ['route' => 'agent.notifications.index', 'label' => 'Notifications', 'icon' => 'bell', 'prefix' => 'agent-de-security/notifications', 'badge' => true],
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
            @if(!empty($link['badge']) && $unreadNotifications > 0)
                <span class="flex h-5 min-w-5 items-center justify-center rounded-full bg-amber-500 px-1.5 text-xs font-semibold text-white">
                    {{ $unreadNotifications > 99 ? '99+' : $unreadNotifications }}
                </span>
            @endif
            @if($active)
                <svg class="h-4 w-4 text-brand-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
            @endif
        </a>
    @endforeach
</nav>
