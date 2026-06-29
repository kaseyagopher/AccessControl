@props(['demande', 'compact' => false])

@php
    $labelValidation = match ($demande->statut) {
        'refuse' => 'Refusé par',
        default => 'Validé par',
    };
@endphp

<div {{ $attributes->merge(['class' => 'space-y-2 text-sm text-slate-600']) }}>
    @if ($demande->validateur && $demande->date_validation)
        <div class="{{ $compact ? '' : 'rounded-lg border border-slate-100 bg-slate-50 px-3 py-2' }}">
            <span class="font-medium text-slate-700">{{ $labelValidation }} :</span>
            {{ $demande->validateur->nomComplet() }}
            <span class="text-slate-500">— {{ $demande->date_validation->format('d/m/Y H:i') }}</span>
        </div>
    @endif

    @if ($demande->heure_arrivee)
        <div class="{{ $compact ? '' : 'rounded-lg border border-emerald-100 bg-emerald-50/50 px-3 py-2' }}">
            <span class="font-medium text-emerald-800">Entrée validée</span>
            <span class="text-emerald-700">— {{ $demande->heure_arrivee->format('d/m/Y H:i') }}</span>
            @if ($demande->agentArrivee)
                <span class="text-slate-600">par {{ $demande->agentArrivee->nomComplet() }}</span>
            @endif
        </div>
    @endif

    @if ($demande->heure_sortie)
        <div class="{{ $compact ? '' : 'rounded-lg border border-slate-200 bg-slate-50 px-3 py-2' }}">
            <span class="font-medium text-slate-800">Sortie validée</span>
            <span class="text-slate-600">— {{ $demande->heure_sortie->format('d/m/Y H:i') }}</span>
            @if ($demande->agentSortie)
                <span class="text-slate-600">par {{ $demande->agentSortie->nomComplet() }}</span>
            @endif
        </div>
    @endif
</div>
