@extends('layouts.auth')

@section('title', 'Accueil')

@section('content')
<div class="min-h-screen bg-[#f5f5f5]">

    {{-- Navigation --}}
    <header class="sticky top-0 z-50 border-b border-slate-200/60 bg-white/80 backdrop-blur-md">
        <div class="mx-auto flex h-16 max-w-6xl items-center justify-between px-4 sm:px-6">
            <a href="/" class="flex items-center text-slate-900">
                <x-mmg-logo class="h-9 w-auto text-brand-600" />
            </a>
            @auth
                <a href="{{
                    match (Auth::user()->role) {
                        'admin' => '/admin',
                        'superviseur' => '/superviseur',
                        'agent-de-security' => '/agent-de-security',
                        default => '/',
                    }
                }}" class="btn-primary">Mon tableau de bord</a>
            @else
                <a href="{{ route('login') }}" class="btn-primary">Connexion</a>
            @endauth
        </div>
    </header>

    {{-- Hero --}}
    <section class="relative overflow-hidden">
        <div class="pointer-events-none absolute inset-0">
            <div class="absolute -left-32 top-0 h-96 w-96 rounded-full bg-brand-100/60 blur-3xl"></div>
            <div class="absolute -right-20 bottom-0 h-80 w-80 rounded-full bg-brand-500/10 blur-3xl"></div>
        </div>

        <div class="relative mx-auto grid max-w-6xl items-center gap-12 px-4 py-16 sm:px-6 lg:grid-cols-2 lg:py-24">
            <div>
                <span class="inline-flex items-center gap-2 rounded-full border border-brand-200 bg-brand-50 px-3 py-1 text-xs font-semibold text-brand-700">
                    <span class="h-1.5 w-1.5 rounded-full bg-brand-600"></span>
                    Solution de gestion des accès
                </span>
                <h1 class="mt-6 text-4xl font-bold tracking-tight text-slate-900 sm:text-5xl">
                    Contrôlez les visites et la sécurité
                    <span class="text-brand-600">en un seul endroit</span>
                </h1>
                <p class="mt-5 max-w-lg text-base leading-relaxed text-slate-600 sm:text-lg">
                    AccessControl centralise les demandes de visite, le suivi des entrées-sorties et la gestion des correspondances entre superviseurs et agents de sécurité.
                </p>

                <div class="mt-8 flex flex-wrap gap-3">
                    @auth
                        <a href="{{
                            match (Auth::user()->role) {
                                'admin' => '/admin',
                                'superviseur' => '/superviseur',
                                'agent-de-security' => '/agent-de-security',
                                default => '/',
                            }
                        }}" class="btn-primary px-6 py-3">Accéder à mon espace</a>
                    @else
                        <a href="{{ route('login') }}" class="btn-primary px-6 py-3">Se connecter</a>
                        @if (app()->environment('local') && ! \App\Models\User::where('role', 'admin')->exists())
                            <a href="{{ route('setup.admin') }}" class="btn-secondary px-6 py-3">Configurer l'admin</a>
                        @endif
                    @endauth
                </div>

                <dl class="mt-10 grid grid-cols-3 gap-4 border-t border-slate-200/80 pt-8">
                    <div>
                        <dt class="text-2xl font-bold text-brand-600">3</dt>
                        <dd class="mt-1 text-xs text-slate-500 sm:text-sm">Rôles dédiés</dd>
                    </div>
                    <div>
                        <dt class="text-2xl font-bold text-brand-600">24/7</dt>
                        <dd class="mt-1 text-xs text-slate-500 sm:text-sm">Suivi des accès</dd>
                    </div>
                    <div>
                        <dt class="text-2xl font-bold text-brand-600">100%</dt>
                        <dd class="mt-1 text-xs text-slate-500 sm:text-sm">Traçabilité</dd>
                    </div>
                </dl>
            </div>

            <div class="relative">
                <div class="rounded-[2rem] bg-gradient-to-br from-mmg-charcoal via-mmg-charcoal to-brand-700 p-6 shadow-2xl shadow-mmg-charcoal/20 sm:p-8">
                    <x-auth-illustration />
                </div>
            </div>
        </div>
    </section>

    {{-- Fonctionnalités par rôle --}}
    <section class="border-t border-slate-200/60 bg-white py-16 sm:py-20">
        <div class="mx-auto max-w-6xl px-4 sm:px-6">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="text-2xl font-bold text-slate-900 sm:text-3xl">Un outil adapté à chaque profil</h2>
                <p class="mt-3 text-slate-500">Chaque utilisateur dispose d'un espace personnalisé selon son rôle dans l'organisation.</p>
            </div>

            <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <div class="card group transition hover:border-brand-200 hover:shadow-md">
                    <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-brand-50 text-brand-600 transition group-hover:bg-brand-600 group-hover:text-white">
                        <x-nav-icon name="users" class="h-5 w-5" />
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900">Administrateur</h3>
                    <p class="mt-2 text-sm leading-relaxed text-slate-500">
                        Gérez les comptes utilisateurs, consultez les rapports d'activité et exportez les données en PDF ou Excel.
                    </p>
                    <ul class="mt-4 space-y-2 text-sm text-slate-600">
                        <li class="flex items-center gap-2">
                            <span class="h-1.5 w-1.5 rounded-full bg-brand-500"></span>
                            Création et gestion des utilisateurs
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="h-1.5 w-1.5 rounded-full bg-brand-500"></span>
                            Rapports et statistiques
                        </li>
                    </ul>
                </div>

                <div class="card group transition hover:border-brand-200 hover:shadow-md">
                    <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-brand-50 text-brand-600 transition group-hover:bg-brand-600 group-hover:text-white">
                        <x-nav-icon name="calendar-plus" class="h-5 w-5" />
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900">Superviseur</h3>
                    <p class="mt-2 text-sm leading-relaxed text-slate-500">
                        Pré-enregistrez les visiteurs, suivez l'historique des demandes et transmettez des lettres à la sécurité.
                    </p>
                    <ul class="mt-4 space-y-2 text-sm text-slate-600">
                        <li class="flex items-center gap-2">
                            <span class="h-1.5 w-1.5 rounded-full bg-brand-500"></span>
                            Demandes de visite en ligne
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="h-1.5 w-1.5 rounded-full bg-brand-500"></span>
                            Envoi de correspondances
                        </li>
                    </ul>
                </div>

                <div class="card group transition hover:border-brand-200 hover:shadow-md sm:col-span-2 lg:col-span-1">
                    <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-brand-50 text-brand-600 transition group-hover:bg-brand-600 group-hover:text-white">
                        <x-nav-icon name="shield" class="h-5 w-5" />
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900">Agent de sécurité</h3>
                    <p class="mt-2 text-sm leading-relaxed text-slate-500">
                        Validez ou refusez les demandes, enregistrez les arrivées et sorties, et traitez les lettres reçues.
                    </p>
                    <ul class="mt-4 space-y-2 text-sm text-slate-600">
                        <li class="flex items-center gap-2">
                            <span class="h-1.5 w-1.5 rounded-full bg-brand-500"></span>
                            Contrôle des accès en temps réel
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="h-1.5 w-1.5 rounded-full bg-brand-500"></span>
                            Visites du jour et notifications
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- Comment ça marche --}}
    <section class="py-16 sm:py-20">
        <div class="mx-auto max-w-6xl px-4 sm:px-6">
            <h2 class="text-center text-2xl font-bold text-slate-900 sm:text-3xl">Comment ça fonctionne ?</h2>
            <div class="mt-12 grid gap-8 sm:grid-cols-3">
                <div class="text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-brand-600 text-lg font-bold text-white shadow-lg shadow-brand-600/30">1</div>
                    <h3 class="mt-4 font-semibold text-slate-900">Demande</h3>
                    <p class="mt-2 text-sm text-slate-500">Le superviseur enregistre un visiteur et envoie la demande à la sécurité.</p>
                </div>
                <div class="text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-brand-600 text-lg font-bold text-white shadow-lg shadow-brand-600/30">2</div>
                    <h3 class="mt-4 font-semibold text-slate-900">Validation</h3>
                    <p class="mt-2 text-sm text-slate-500">L'agent de sécurité approuve ou refuse l'accès avec un commentaire.</p>
                </div>
                <div class="text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-brand-600 text-lg font-bold text-white shadow-lg shadow-brand-600/30">3</div>
                    <h3 class="mt-4 font-semibold text-slate-900">Suivi</h3>
                    <p class="mt-2 text-sm text-slate-500">Arrivée et sortie sont enregistrées pour une traçabilité complète.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    @guest
        <section class="mx-4 mb-16 sm:mx-6">
            <div class="mx-auto max-w-6xl overflow-hidden rounded-3xl bg-gradient-to-r from-mmg-charcoal to-brand-600 px-8 py-12 text-center shadow-xl shadow-mmg-charcoal/25 sm:px-16">
                <h2 class="text-2xl font-bold text-white sm:text-3xl">Prêt à sécuriser vos accès ?</h2>
                <p class="mx-auto mt-3 max-w-lg text-brand-100">Connectez-vous pour accéder à votre espace de travail.</p>
                <a href="{{ route('login') }}" class="mt-8 inline-flex items-center justify-center rounded-xl bg-white px-8 py-3 text-sm font-semibold text-brand-700 shadow-sm transition hover:bg-brand-50">
                    Se connecter maintenant
                </a>
            </div>
        </section>
    @endguest

    {{-- Footer --}}
    <footer class="border-t border-slate-200/60 bg-white py-8">
        <div class="mx-auto flex max-w-6xl flex-col items-center justify-between gap-4 px-4 sm:flex-row sm:px-6">
            <div class="flex items-center gap-2 text-sm font-semibold text-slate-700">
                <x-mmg-logo class="h-7 w-auto text-brand-600" />
            </div>
            <p class="text-sm text-slate-500">© {{ date('Y') }} MMG — Gestion des accès et de la sécurité</p>
        </div>
    </footer>
</div>
@endsection
