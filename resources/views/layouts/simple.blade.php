<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AccessControl') — AccessControl</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f4f6fb]">
@include('layouts.partials.loading-overlay')

@auth
    {{-- Overlay mobile --}}
    <div id="sidebar-overlay" class="fixed inset-0 z-40 hidden bg-slate-900/40 lg:hidden"></div>

    {{-- Aside fixe à gauche --}}
    <aside id="sidebar"
           class="fixed inset-y-0 left-0 z-50 flex w-64 -translate-x-full flex-col border-r border-slate-200/80 bg-white transition-transform duration-300 lg:translate-x-0">

        {{-- Logo --}}
        <div class="flex h-16 items-center gap-3 border-b border-slate-100 px-5">
            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-brand-600 text-sm font-bold text-white shadow-md shadow-brand-600/30">
                <x-nav-icon name="shield" class="h-5 w-5 text-white" />
            </span>
            <span class="text-lg font-bold tracking-tight text-slate-900">AccessControl</span>
        </div>

        {{-- Navigation --}}
        @include('layouts.partials.sidebar')

        {{-- Profil utilisateur en bas --}}
        <div class="mt-auto border-t border-slate-100 p-4">
            <div class="flex items-center gap-3 rounded-xl bg-slate-50 p-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-brand-100 text-sm font-bold text-brand-700">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-semibold text-slate-900">{{ Auth::user()->name }}</p>
                    <p class="truncate text-xs text-slate-500 capitalize">{{ str_replace('-', ' ', Auth::user()->role) }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl border border-slate-200 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                    </svg>
                    Déconnexion
                </button>
            </form>
        </div>
    </aside>

    {{-- Zone principale --}}
    <div class="lg:pl-64">
        {{-- Header mobile --}}
        <header class="sticky top-0 z-30 flex h-16 items-center justify-between border-b border-slate-200/80 bg-white/90 px-4 backdrop-blur-md lg:px-8">
            <button id="sidebar-toggle" type="button" class="rounded-xl border border-slate-200 p-2 text-slate-600 hover:bg-slate-50 lg:hidden">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12" />
                </svg>
            </button>
            <p class="text-sm font-semibold text-slate-900 lg:text-base">@yield('title', 'AccessControl')</p>
            <div class="w-9 lg:hidden"></div>
        </header>

        <main class="px-4 py-6 sm:px-6 sm:py-8 lg:px-8">
            @if (session('success'))
                <x-alert type="success">{{ session('success') }}</x-alert>
            @endif
            @if (session('error'))
                <x-alert type="error">{{ session('error') }}</x-alert>
            @endif
            @include('layouts.partials.validation-errors')
            @yield('content')
        </main>
    </div>

@else
    {{-- Layout invité (login, accueil) --}}
    <header class="border-b border-slate-200/80 bg-white">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6">
            <a href="/" class="flex items-center gap-2 font-bold text-slate-900">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-brand-600 text-sm text-white">AC</span>
                AccessControl
            </a>
            <a href="{{ route('login') }}" class="btn-primary">Connexion</a>
        </div>
    </header>
    <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 sm:py-8">
        @if (session('success'))
            <x-alert type="success">{{ session('success') }}</x-alert>
        @endif
        @if (session('error'))
            <x-alert type="error">{{ session('error') }}</x-alert>
        @endif
        @include('layouts.partials.validation-errors')
        @yield('content')
    </main>
@endauth

</body>
</html>
