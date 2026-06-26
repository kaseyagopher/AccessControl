@props(['status'])

@php
    $styles = match($status) {
        'brouillon' => 'bg-slate-100 text-slate-700',
        'en_attente', 'envoyee' => 'bg-brand-100 text-brand-800',
        'recu', 'recue' => 'bg-mmg-charcoal/10 text-mmg-charcoal',
        'valide', 'traitee' => 'bg-brand-50 text-brand-700 ring-1 ring-brand-200',
        'refuse', 'refusee' => 'bg-brand-800 text-white',
        'termine' => 'bg-mmg-charcoal text-white',
        'admin' => 'bg-mmg-charcoal text-white',
        'superviseur' => 'bg-brand-100 text-brand-800',
        'agent-de-security' => 'bg-brand-50 text-brand-700 ring-1 ring-brand-200',
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
