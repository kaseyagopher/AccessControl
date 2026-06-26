@extends('layouts.simple')

@section('title', 'Utilisateurs')

@section('content')
<x-page-header title="Liste des utilisateurs" icon="users" color="violet">
    <x-slot:actions>
        <a href="{{ route('admin.enregistrement') }}" class="btn-primary inline-flex items-center gap-2">
            <x-nav-icon name="user-plus" class="h-4 w-4" />
            Créer un utilisateur
        </a>
    </x-slot:actions>
</x-page-header>

<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
    @forelse ($users as $user)
        <div class="card flex flex-col {{ $user->trashed() ? 'opacity-60' : '' }}">
            <div class="flex items-start gap-3">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-brand-50 text-sm font-bold text-brand-700">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <h3 class="font-semibold text-slate-900">{{ $user->name }}</h3>
                        <x-badge :status="$user->role" />
                    </div>
                    <p class="mt-1 truncate text-sm text-slate-500">{{ $user->email }}</p>
                    @if ($user->matricule)
                        <p class="mt-0.5 text-xs text-slate-400">Matricule : {{ $user->matricule }}</p>
                    @endif
                    @if ($user->trashed())
                        <p class="mt-2 text-xs font-medium text-brand-700">Compte désactivé</p>
                    @endif
                </div>
            </div>

            <div class="mt-4 flex flex-wrap gap-2 border-t border-slate-100 pt-4">
                <a href="{{ route('admin.update-user', $user->id) }}" class="btn-secondary inline-flex items-center gap-1 text-xs">
                    <x-nav-icon name="pencil" class="h-3.5 w-3.5" /> Modifier
                </a>
                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Supprimer définitivement {{ $user->name }} ?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-danger text-xs">Supprimer</button>
                </form>
                @if ($user->trashed())
                    <form action="{{ route('admin.users.restore', $user->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn-secondary text-xs">Réactiver</button>
                    </form>
                @else
                    <form action="{{ route('admin.users.disable', $user->id) }}" method="POST" onsubmit="return confirm('Désactiver {{ $user->name }} ?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-secondary text-xs">Désactiver</button>
                    </form>
                @endif
            </div>
        </div>
    @empty
        <x-empty-state icon="users" color="violet" message="Aucun utilisateur trouvé." />
    @endforelse
</div>
@endsection
