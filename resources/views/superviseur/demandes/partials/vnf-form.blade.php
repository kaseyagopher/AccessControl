@props(['demande' => null, 'departements', 'services'])

@php
    $visiteurs = $demande
        ? ($demande->visiteurs->isNotEmpty() ? $demande->visiteurs : collect([$demande->visiteur]))
        : collect([null]);
    $selectedServiceId = old('service_id', $demande?->service_id);
    $selectedDepartementId = old('departement_id', $demande?->service?->departement_id);
@endphp

<div>
    <div class="mb-4 flex items-center justify-between">
        <h2 class="text-lg font-semibold text-slate-900">Visiteurs</h2>
        <button type="button" id="add-visiteur" class="btn-secondary inline-flex items-center gap-2 text-sm">
            <x-nav-icon name="user-plus" class="h-4 w-4" />
            Ajouter un visiteur
        </button>
    </div>

    <div id="visiteurs-container" class="space-y-4">
        @foreach ($visiteurs as $index => $v)
            <div class="visiteur-block rounded-xl border border-slate-200 bg-slate-50/50 p-5" data-index="{{ $index }}">
                <div class="mb-4 flex items-center justify-between">
                    <span class="visiteur-label text-sm font-semibold text-brand-700">Visiteur {{ $index + 1 }}</span>
                    <button type="button" class="remove-visiteur {{ $visiteurs->count() <= 1 ? 'hidden' : '' }} text-sm text-brand-700 hover:text-brand-800">Retirer</button>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <x-form-field label="Nom" :name="'visiteurs['.$index.'][nom]'" :value="old('visiteurs.'.$index.'.nom', $v?->nom)" required />
                    <x-form-field label="Postnom" :name="'visiteurs['.$index.'][postnom]'" :value="old('visiteurs.'.$index.'.postnom', $v?->postnom)" />
                    <x-form-field label="Prénom" :name="'visiteurs['.$index.'][prenom]'" :value="old('visiteurs.'.$index.'.prenom', $v?->prenom)" required />
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Genre</label>
                        <select name="visiteurs[{{ $index }}][genre]" class="input-field">
                            <option value="">—</option>
                            @foreach (['M' => 'Masculin', 'F' => 'Féminin', 'Autre' => 'Autre'] as $val => $label)
                                <option value="{{ $val }}" @selected(old('visiteurs.'.$index.'.genre', $v?->genre) === $val)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <x-form-field label="Téléphone" :name="'visiteurs['.$index.'][telephone]'" :value="old('visiteurs.'.$index.'.telephone', $v?->telephone)" required />
                    <x-form-field label="Entreprise" :name="'visiteurs['.$index.'][entreprise]'" :value="old('visiteurs.'.$index.'.entreprise', $v?->entreprise)" required />
                    <x-form-field label="Fonction / Poste" :name="'visiteurs['.$index.'][fonction]'" :value="old('visiteurs.'.$index.'.fonction', $v?->fonction)" full />
                </div>
            </div>
        @endforeach
    </div>
    @error('visiteurs')
        <p class="mt-2 text-sm text-brand-700">{{ $message }}</p>
    @enderror
</div>

<div class="border-t border-slate-100 pt-6">
    <h2 class="mb-4 text-lg font-semibold text-slate-900">Détails de la VNF</h2>
    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="departement_id" class="mb-1.5 block text-sm font-medium text-slate-700">Département <span class="text-brand-600">*</span></label>
            <select id="departement_id" class="input-field @error('service_id') border-brand-400 @enderror">
                <option value="">Choisir un département</option>
                @foreach ($departements as $departement)
                    <option value="{{ $departement->id }}" @selected((string) $selectedDepartementId === (string) $departement->id)>{{ $departement->nom }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="service_id" class="mb-1.5 block text-sm font-medium text-slate-700">Service <span class="text-brand-600">*</span></label>
            <select id="service_id" name="service_id" required class="input-field @error('service_id') border-brand-400 @enderror">
                <option value="">Choisir un service</option>
                @foreach ($services as $service)
                    <option value="{{ $service->id }}" data-departement="{{ $service->departement_id }}" @selected((string) $selectedServiceId === (string) $service->id)>{{ $service->nom }}</option>
                @endforeach
            </select>
            @error('service_id')
                <p class="mt-1 text-sm text-brand-700">{{ $message }}</p>
            @enderror
        </div>
        <x-form-field label="Motif" name="motif" type="textarea" :value="old('motif', $demande?->motif)" required full :rows="3" />
        <x-form-field label="Date de visite" name="date_prevue" type="date" :value="old('date_prevue', $demande?->date_prevue?->format('Y-m-d'))" required />
        <x-form-field label="Heure d'entrée prévue" name="heure_prevue" type="time" :value="old('heure_prevue', $demande?->heure_prevue)" required />
        <div class="sm:col-span-2">
            <label for="document" class="mb-1.5 block text-sm font-medium text-slate-700">Document joint</label>
            <input id="document" type="file" name="document" class="input-field file:mr-3 file:rounded-lg file:border-0 file:bg-brand-50 file:px-3 file:py-1 file:text-sm file:font-medium file:text-brand-700 @error('document') border-brand-400 @enderror">
            @if ($demande?->document_path)
                <p class="mt-2 text-sm text-slate-500">Document actuel : <a href="{{ asset('storage/'.$demande->document_path) }}" target="_blank" class="text-brand-600 hover:underline">Télécharger</a></p>
            @endif
            @error('document')
                <p class="mt-1 text-sm text-brand-700">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>

<template id="visiteur-template">
    <div class="visiteur-block rounded-xl border border-slate-200 bg-slate-50/50 p-5" data-index="__INDEX__">
        <div class="mb-4 flex items-center justify-between">
            <span class="visiteur-label text-sm font-semibold text-brand-700">Visiteur __NUM__</span>
            <button type="button" class="remove-visiteur text-sm text-brand-700 hover:text-brand-800">Retirer</button>
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">Nom <span class="text-brand-600">*</span></label>
                <input type="text" name="visiteurs[__INDEX__][nom]" required class="input-field">
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">Postnom</label>
                <input type="text" name="visiteurs[__INDEX__][postnom]" class="input-field">
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">Prénom <span class="text-brand-600">*</span></label>
                <input type="text" name="visiteurs[__INDEX__][prenom]" required class="input-field">
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">Genre</label>
                <select name="visiteurs[__INDEX__][genre]" class="input-field">
                    <option value="">—</option>
                    <option value="M">Masculin</option>
                    <option value="F">Féminin</option>
                    <option value="Autre">Autre</option>
                </select>
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">Téléphone <span class="text-brand-600">*</span></label>
                <input type="text" name="visiteurs[__INDEX__][telephone]" required class="input-field">
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">Entreprise <span class="text-brand-600">*</span></label>
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
    const departementSelect = document.getElementById('departement_id');
    const serviceSelect = document.getElementById('service_id');
    let nextIndex = container?.querySelectorAll('.visiteur-block').length ?? 0;

    const filterServices = () => {
        if (!departementSelect || !serviceSelect) return;
        const depId = departementSelect.value;
        Array.from(serviceSelect.options).forEach((option, i) => {
            if (i === 0) return;
            const match = !depId || option.dataset.departement === depId;
            option.hidden = !match;
            if (!match && option.selected) serviceSelect.value = '';
        });
    };

    departementSelect?.addEventListener('change', filterServices);
    filterServices();

    const updateLabels = () => {
        container?.querySelectorAll('.visiteur-block').forEach((block, i) => {
            const label = block.querySelector('.visiteur-label');
            if (label) label.textContent = 'Visiteur ' + (i + 1);
            const removeBtn = block.querySelector('.remove-visiteur');
            if (removeBtn) removeBtn.classList.toggle('hidden', container.querySelectorAll('.visiteur-block').length <= 1);
        });
    };

    addBtn?.addEventListener('click', () => {
        if (!template || nextIndex >= 50) return;
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
