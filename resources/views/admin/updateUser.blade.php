@extends('layouts.simple')

@section('title', 'Modifier utilisateur')

@section('content')
<x-page-header title="Modifier l'utilisateur" :subtitle="$user->name" icon="pencil" color="violet" />

<div class="card max-w-2xl">
    <form action="{{ route('admin.update-user', $user->id) }}" method="post" class="space-y-5">
        @csrf @method('PUT')
        <div class="grid gap-5 sm:grid-cols-2">
            <x-form-field label="Nom" name="name" :value="old('name', $user->name)" required />
            <x-form-field label="Postnom" name="lastName" :value="old('lastName', $user->lastName)" />
            <x-form-field label="Prénom" name="firstName" :value="old('firstName', $user->firstName)" />
            <x-form-field label="Email" name="email" type="email" :value="old('email', $user->email)" required />
            <x-form-field label="Nom d'utilisateur" name="username" :value="old('username', $user->username)" />
            <x-form-field label="Fonction" name="fonction" :value="old('fonction', $user->fonction)" />
            <x-form-field label="Nouveau mot de passe" name="password" type="password" full placeholder="Laisser vide pour ne pas changer" />
            <x-form-field label="Confirmer le mot de passe" name="password_confirmation" type="password" full />
            <x-form-field label="Matricule" name="matricule" :value="old('matricule', $user->matricule)" />
            <div>
                <label for="role" class="mb-1.5 block text-sm font-medium text-slate-700">Rôle <span class="text-brand-600">*</span></label>
                <select id="role" name="role" required class="input-field @error('role') border-brand-400 @enderror">
                    <option value="admin" @selected(old('role', $user->role) === 'admin')>Administrateur</option>
                    <option value="agent-de-security" @selected(old('role', $user->role) === 'agent-de-security')>Agent de sécurité</option>
                    <option value="superviseur" @selected(old('role', $user->role) === 'superviseur')>Superviseur</option>
                </select>
                @error('role')
                    <p class="mt-1 text-sm text-brand-700">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <button type="submit" class="btn-primary">Mettre à jour</button>
    </form>
</div>
@endsection
