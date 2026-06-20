@extends('layouts.simple')

@section('title', 'Nouvelle demande')

@section('content')
<x-page-header title="Pré-enregistrement visiteur" subtitle="Une visite peut comporter plusieurs personnes" icon="calendar-plus" color="emerald">
    <x-slot:actions>
        <a href="{{ route('superviseur.dashboard') }}" class="btn-secondary">Retour</a>
    </x-slot:actions>
</x-page-header>

<div class="card max-w-3xl">
    <form id="demande-form" method="POST" action="{{ route('superviseur.demandes.store') }}" enctype="multipart/form-data" class="space-y-8">
        @csrf

        {{-- Visiteurs --}}
        <div>
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-slate-900">Visiteurs</h2>
                <button type="button" id="add-visiteur" class="btn-secondary inline-flex items-center gap-2 text-sm">
                    <x-nav-icon name="user-plus" class="h-4 w-4" />
                    Ajouter un visiteur
                </button>
            </div>

            <div id="visiteurs-container" class="space-y-4">
                <div class="visiteur-block rounded-xl border border-slate-200 bg-slate-50/50 p-5" data-index="0">
                    <div class="mb-4 flex items-center justify-between">
                        <span class="text-sm font-semibold text-brand-700">Visiteur 1</span>
                        <button type="button" class="remove-visiteur hidden text-sm text-red-600 hover:text-red-700">Retirer</button>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <x-form-field label="Nom" name="visiteurs[0][nom]" :value="old('visiteurs.0.nom')" required />
                        <x-form-field label="Prénom" name="visiteurs[0][prenom]" :value="old('visiteurs.0.prenom')" required />
                        <x-form-field label="Téléphone" name="visiteurs[0][telephone]" :value="old('visiteurs.0.telephone')" required />
                        <x-form-field label="Entreprise" name="visiteurs[0][entreprise]" :value="old('visiteurs.0.entreprise')" required />
                        <x-form-field label="Fonction / Poste" name="visiteurs[0][fonction]" :value="old('visiteurs.0.fonction')" full />
                    </div>
                </div>
            </div>
            @error('visiteurs')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Détails de la visite --}}
        <div class="border-t border-slate-100 pt-6">
            <h2 class="mb-4 text-lg font-semibold text-slate-900">Détails de la visite</h2>
            <div class="grid gap-5 sm:grid-cols-2">
                <x-form-field label="Motif" name="motif" type="textarea" :value="old('motif')" required full :rows="3" />
                <x-form-field label="Date prévue" name="date_prevue" type="date" :value="old('date_prevue')" required />
                <x-form-field label="Heure prévue" name="heure_prevue" type="time" :value="old('heure_prevue')" required />
                <div class="sm:col-span-2">
                    <label for="document" class="mb-1.5 block text-sm font-medium text-slate-700">Document joint</label>
                    <input id="document" type="file" name="document" class="input-field file:mr-3 file:rounded-lg file:border-0 file:bg-brand-50 file:px-3 file:py-1 file:text-sm file:font-medium file:text-brand-700 @error('document') border-red-300 @enderror">
                    @error('document')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <button type="submit" class="btn-primary">Envoyer à la sécurité</button>
    </form>
</div>

<template id="visiteur-template">
    <div class="visiteur-block rounded-xl border border-slate-200 bg-slate-50/50 p-5" data-index="__INDEX__">
        <div class="mb-4 flex items-center justify-between">
            <span class="visiteur-label text-sm font-semibold text-brand-700">Visiteur __NUM__</span>
            <button type="button" class="remove-visiteur text-sm text-red-600 hover:text-red-700">Retirer</button>
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">Nom <span class="text-red-500">*</span></label>
                <input type="text" name="visiteurs[__INDEX__][nom]" required class="input-field">
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">Prénom <span class="text-red-500">*</span></label>
                <input type="text" name="visiteurs[__INDEX__][prenom]" required class="input-field">
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">Téléphone <span class="text-red-500">*</span></label>
                <input type="text" name="visiteurs[__INDEX__][telephone]" required class="input-field">
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">Entreprise <span class="text-red-500">*</span></label>
                <input type="text" name="visiteurs[__INDEX__][entreprise]" required class="input-field">
            </div>
            <div class="sm:col-span-2">
                <label class="mb-1.5 block text-sm font-medium text-slate-700">Fonction / Poste</label>
                <input type="text" name="visiteurs[__INDEX__][fonction]" class="input-field">
            </div>
        </div>
    </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('visiteurs-container');
    const template = document.getElementById('visiteur-template');
    const addBtn = document.getElementById('add-visiteur');
    let nextIndex = container.querySelectorAll('.visiteur-block').length;

    const updateLabels = () => {
        const blocks = container.querySelectorAll('.visiteur-block');
        blocks.forEach((block, i) => {
            const label = block.querySelector('.visiteur-label') || block.querySelector('span.text-brand-700');
            if (label) label.textContent = 'Visiteur ' + (i + 1);
            const removeBtn = block.querySelector('.remove-visiteur');
            if (removeBtn) removeBtn.classList.toggle('hidden', blocks.length <= 1);
        });
    };

    addBtn?.addEventListener('click', () => {
        if (nextIndex >= 50) return;
        const html = template.innerHTML
            .replace(/__INDEX__/g, nextIndex)
            .replace(/__NUM__/g, nextIndex + 1);
        container.insertAdjacentHTML('beforeend', html);
        nextIndex++;
        updateLabels();
    });

    container?.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove-visiteur')) {
            e.target.closest('.visiteur-block')?.remove();
            updateLabels();
        }
    });

    updateLabels();
});
</script>
@endsection
