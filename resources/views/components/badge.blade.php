@props(['status'])

@php
    $styles = match($status) {
        'en_attente', 'envoyee' => 'bg-amber-100 text-amber-800',
        'recu', 'recue' => 'bg-blue-100 text-blue-800',
        'valide', 'traitee' => 'bg-emerald-100 text-emerald-800',
        'refuse', 'refusee' => 'bg-red-100 text-red-800',
        'termine' => 'bg-slate-100 text-slate-700',
        'admin' => 'bg-purple-100 text-purple-800',
        'superviseur' => 'bg-indigo-100 text-indigo-800',
        'agent-de-security' => 'bg-cyan-100 text-cyan-800',
        default => 'bg-slate-100 text-slate-700',
    };
    $label = str_replace('-', ' ', $status);
@endphp

<span {{ $attributes->merge(['class' => "inline-flex rounded-full px-2.5 py-1 text-xs font-semibold capitalize {$styles}"]) }}>
    {{ $label }}
</span>
