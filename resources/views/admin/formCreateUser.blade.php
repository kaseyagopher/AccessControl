@extends('layouts.simple')

@section('title', 'Créer utilisateur')

@section('content')
<x-page-header title="Créer un utilisateur" subtitle="Administrateur, superviseur ou agent de sécurité" icon="user-plus" color="emerald" />

<div class="card max-w-2xl">
    <form action="{{ route('admin.enregistrement') }}" method="post" class="space-y-5">
        @csrf
        <div class="grid gap-5 sm:grid-cols-2">
            <x-form-field label="Nom" name="name" :value="old('name')" required />
            <x-form-field label="Postnom" name="lastName" :value="old('lastName')" />
            <x-form-field label="Prénom" name="firstName" :value="old('firstName')" />
            <x-form-field label="Email" name="email" type="email" :value="old('email')" required />
            <x-form-field label="Mot de passe" name="password" type="password" required />
            <x-form-field label="Confirmer le mot de passe" name="password_confirmation" type="password" required />
            <x-form-field label="Matricule" name="matricule" :value="old('matricule')" placeholder="ACC-565X" />
            <div class="sm:col-span-2">
                <label for="role" class="mb-1.5 block text-sm font-medium text-slate-700">Rôle <span class="text-red-500">*</span></label>
                <select id="role" name="role" required class="input-field @error('role') border-red-300 @enderror">
                    <option value="" disabled @selected(! old('role'))>Choisir un rôle</option>
                    <option value="admin" @selected(old('role') === 'admin')>Administrateur</option>
                    <option value="agent-de-security" @selected(old('role') === 'agent-de-security')>Agent de sécurité</option>
                    <option value="superviseur" @selected(old('role') === 'superviseur')>Superviseur</option>
                </select>
                @error('role')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <button type="submit" class="btn-primary">Enregistrer</button>
    </form>
</div>
@endsection
