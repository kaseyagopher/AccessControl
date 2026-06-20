@extends('layouts.auth')

@section('title', 'Connexion')

@section('content')
<div class="flex min-h-screen flex-col lg:flex-row">

    {{-- Panneau illustration (gauche) --}}
    <div class="relative hidden overflow-hidden lg:flex lg:w-[52%]">
        <div class="absolute inset-0 bg-gradient-to-b from-brand-50 via-brand-100 to-brand-700"></div>
        <div class="relative z-10 flex w-full flex-col items-center justify-center px-12 py-16">
            <div class="mb-8 flex items-center gap-2">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-600 text-sm font-bold text-white shadow-lg shadow-brand-600/30">
                    <x-nav-icon name="shield" class="h-5 w-5 text-white" />
                </span>
                <span class="text-xl font-bold tracking-tight text-brand-700">AccessControl</span>
            </div>
            <x-auth-illustration />
            <p class="mt-10 max-w-sm text-center text-base font-medium leading-relaxed text-brand-700/80">
                Gérez les accès, les visites et la sécurité de votre établissement en toute simplicité.
            </p>
        </div>
    </div>

    {{-- Panneau formulaire (droite) --}}
    <div class="flex flex-1 items-center justify-center bg-[#f4f6fb] px-5 py-10 sm:px-8">
        <div class="w-full max-w-[420px]">

            <div class="mb-8 flex items-center justify-center gap-2 lg:hidden">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-600 text-sm font-bold text-white">
                    <x-nav-icon name="shield" class="h-5 w-5 text-white" />
                </span>
                <span class="text-xl font-bold text-brand-700">AccessControl</span>
            </div>

            <div class="rounded-[2rem] bg-white px-8 py-10 shadow-xl shadow-brand-700/10 sm:px-10 sm:py-12">
                <div class="mb-8">
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Connexion</h1>
                    <p class="mt-2 text-sm text-slate-500">Accédez à votre espace de gestion</p>
                </div>

                @if (session('success'))
                    <x-alert type="success" class="mb-5">{{ session('success') }}</x-alert>
                @endif
                @if (session('error'))
                    <x-alert type="error" class="mb-5">{{ session('error') }}</x-alert>
                @endif
                @include('layouts.partials.validation-errors')

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="mb-2 block text-sm font-medium text-slate-700">Adresse email</label>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            value="{{ old('email') }}"
                            required
                            autocomplete="email"
                            placeholder="vous@exemple.com"
                            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 shadow-sm transition placeholder:text-slate-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/25 @error('email') border-red-300 @enderror"
                        >
                        @error('email')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="mb-2 block text-sm font-medium text-slate-700">Mot de passe</label>
                        <div class="relative">
                            <input
                                type="password"
                                name="password"
                                id="password"
                                required
                                autocomplete="current-password"
                                placeholder="••••••••"
                                class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 pr-11 text-sm text-slate-800 shadow-sm transition placeholder:text-slate-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/25 @error('password') border-red-300 @enderror"
                            >
                            <button
                                type="button"
                                id="toggle-password"
                                class="absolute right-3 top-1/2 -translate-y-1/2 rounded-lg p-1 text-slate-400 transition hover:text-slate-600"
                                aria-label="Afficher le mot de passe"
                            >
                                <svg id="eye-open" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <svg id="eye-closed" class="hidden h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="btn-primary w-full rounded-2xl py-3.5 shadow-md shadow-brand-600/30">
                        Se connecter
                    </button>
                </form>

                @if (app()->environment('local') && ! \App\Models\User::where('role', 'admin')->exists())
                    <p class="mt-8 text-center text-sm text-slate-500">
                        Première installation ?
                        <a href="{{ route('setup.admin') }}" class="font-semibold text-brand-600 hover:text-brand-700">Créer l'administrateur</a>
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('toggle-password')?.addEventListener('click', function () {
        const input = document.getElementById('password');
        const open = document.getElementById('eye-open');
        const closed = document.getElementById('eye-closed');
        const isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';
        open.classList.toggle('hidden', isPassword);
        closed.classList.toggle('hidden', !isPassword);
    });
</script>
@endsection
