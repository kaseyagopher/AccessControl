@extends('layouts.simple')

@section('title', 'Mes lettres')

@section('content')
<x-page-header title="Mes lettres envoyées" icon="mail" color="violet">
    <x-slot:actions>
        <a href="{{ route('superviseur.lettres.create') }}" class="btn-primary inline-flex items-center gap-2">
            <x-nav-icon name="mail" class="h-4 w-4" /> Nouvelle lettre
        </a>
    </x-slot:actions>
</x-page-header>

<div class="space-y-4">
    @forelse ($lettres as $lettre)
        <x-list-card icon="mail" color="violet">
            <h3 class="font-semibold text-slate-900">{{ $lettre->objet }}</h3>
            <p class="text-sm text-slate-500">Envoyée le {{ $lettre->created_at->format('d/m/Y H:i') }}</p>
            @if ($lettre->observation_securite)
                <p class="mt-2 rounded-lg bg-slate-50 px-3 py-2 text-sm text-slate-600">{{ $lettre->observation_securite }}</p>
            @endif
            <x-slot:aside>
                <x-badge :status="$lettre->statut" />
            </x-slot:aside>
        </x-list-card>
    @empty
        <x-empty-state icon="mail" color="violet" message="Aucune lettre." />
    @endforelse
</div>
@endsection
