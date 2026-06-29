@props(['status'])

@php
    $styles = match($status) {
        'brouillon' => 'bg-slate-100 text-slate-700',
        'en_attente', 'envoyee' => 'bg-amber-100 text-amber-800',
        'recu', 'recue' => 'bg-sky-100 text-sky-800',
        'valide', 'traitee' => 'bg-emerald-100 text-emerald-800',
        'refuse', 'refusee' => 'bg-red-100 text-red-800',
        'termine' => 'bg-slate-200 text-slate-800',
        'admin' => 'bg-mmg-charcoal text-white',
        'superviseur' => 'bg-indigo-100 text-indigo-800',
        'agent-de-security' => 'bg-cyan-100 text-cyan-800',
        default => 'bg-slate-100 text-slate-700',
    };
    $label = match($status) {
        'brouillon' => 'Brouillon',
        'en_attente' => 'En attente',
        'recu' => 'Reçu',
        'valide' => 'Validé',
        'refuse' => 'Non validé',
        'termine' => 'Terminé',
        default => str_replace('-', ' ', $status),
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {$styles}"]) }}>
    {{ $label }}
</span>
