@extends('layouts.simple')

@section('title', 'Notifications')

@section('content')
<x-page-header title="Notifications" subtitle="Consultez vos alertes et messages récents" icon="bell" color="amber" />

<div class="card">
    @if ($notifications->count())
        <div class="space-y-3">
            @foreach ($notifications as $notif)
                <div class="flex flex-col gap-2 rounded-xl border border-slate-100 bg-slate-50 p-4 sm:flex-row sm:items-center sm:justify-between {{ !$notif->lu ? 'border-l-4 border-l-amber-500' : '' }}">
                    <div class="min-w-0 flex-1">
                        <p class="text-sm text-slate-700">
                            @if (!$notif->lu)
                                <span class="mr-2 font-semibold text-amber-600">Nouveau</span>
                            @endif
                            {{ $notif->message }}
                        </p>
                        <p class="mt-1 text-xs text-slate-400">{{ $notif->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @if (!$notif->lu)
                        <form method="POST" action="{{ route($markReadRoute, $notif->id) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn-secondary shrink-0 text-xs">Marquer lu</button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @else
        <div class="flex flex-col items-center justify-center py-12 text-center">
            <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                <x-nav-icon name="bell" class="h-7 w-7" />
            </div>
            <p class="text-sm font-medium text-slate-600">Aucune notification pour le moment</p>
            <p class="mt-1 text-xs text-slate-400">Les alertes liées à vos visites apparaîtront ici.</p>
        </div>
    @endif
</div>
@endsection
