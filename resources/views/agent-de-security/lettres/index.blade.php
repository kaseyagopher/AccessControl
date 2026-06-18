@extends('layouts.simple')

@section('title', 'Lettres reçues')

@section('content')
<x-page-header title="Lettres reçues" icon="mail" color="violet" />

<div class="space-y-4">
    @forelse ($lettres as $lettre)
        <x-list-card icon="mail" color="violet">
            <h3 class="font-semibold text-slate-900">{{ $lettre->objet }}</h3>
            <p class="text-sm text-slate-500">De : {{ $lettre->superviseur->name }}</p>
            <x-slot:aside>
                <x-badge :status="$lettre->statut" />
                <a href="{{ route('agent.lettres.show', $lettre->id) }}" class="btn-primary inline-flex items-center gap-1 text-sm">
                    Traiter <x-nav-icon name="shield" class="h-4 w-4" />
                </a>
            </x-slot:aside>
        </x-list-card>
    @empty
        <x-empty-state icon="mail" color="violet" message="Aucune lettre." />
    @endforelse
</div>
@endsection
