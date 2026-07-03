@props(['notifications', 'markReadRoute', 'viewAllRoute' => null])

@if ($notifications->count())
    <div class="card mb-8">
        <div class="mb-4 flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                    <x-nav-icon name="bell" class="h-5 w-5" />
                </div>
                <h2 class="text-lg font-semibold text-slate-900">Notifications</h2>
            </div>
            @if ($viewAllRoute)
                <a href="{{ route($viewAllRoute) }}" class="text-sm font-medium text-brand-600 hover:text-brand-700">Voir tout</a>
            @endif
        </div>
        <div class="space-y-3">
            @foreach ($notifications as $notif)
                <div class="flex flex-col gap-2 rounded-xl border border-slate-100 bg-slate-50 p-4 sm:flex-row sm:items-center sm:justify-between {{ !$notif->lu ? 'border-l-4 border-l-amber-500' : '' }}">
                    <p class="text-sm text-slate-700">
                        @if (!$notif->lu)<span class="mr-2 font-semibold text-amber-600">Nouveau</span>@endif
                        {{ $notif->message }}
                    </p>
                    @if (!$notif->lu)
                        <form method="POST" action="{{ route($markReadRoute, $notif->id) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn-secondary text-xs">Marquer lu</button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endif
