<div class="card max-w-2xl">
    <form action="{{ $action }}" method="post" class="space-y-5">
        @csrf @method('PUT')
        <div class="grid gap-5 sm:grid-cols-2">
            <x-form-field label="Nom" name="name" :value="old('name', $user->name)" required />
            <x-form-field label="Postnom" name="lastName" :value="old('lastName', $user->lastName)" />
            <x-form-field label="Prénom" name="firstName" :value="old('firstName', $user->firstName)" />
            <x-form-field label="Email" name="email" type="email" :value="old('email', $user->email)" required />
            <x-form-field label="Nouveau mot de passe" name="password" type="password" placeholder="Laisser vide pour ne pas changer" />
            <x-form-field label="Confirmer le mot de passe" name="password_confirmation" type="password" />
            <x-form-field label="Matricule" name="matricule" :value="old('matricule', $user->matricule)" />
            @if($showRole ?? false)
                <div>
                    <label for="role" class="mb-1.5 block text-sm font-medium text-slate-700">Rôle</label>
                    <select id="role" name="role" class="input-field @error('role') border-brand-400 @enderror">
                        <option value="agent-de-security" @selected(old('role', $user->role) === 'agent-de-security')>Agent de sécurité</option>
                        <option value="superviseur" @selected(old('role', $user->role) === 'superviseur')>Superviseur</option>
                        <option value="admin" @selected(old('role', $user->role) === 'admin')>Administrateur</option>
                    </select>
                    @error('role')
                        <p class="mt-1 text-sm text-brand-700">{{ $message }}</p>
                    @enderror
                </div>
            @else
                <input type="hidden" name="role" value="{{ $user->role }}">
            @endif
        </div>
        <button type="submit" class="btn-primary">Enregistrer les modifications</button>
    </form>
</div>
