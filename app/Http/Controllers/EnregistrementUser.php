<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class EnregistrementUser extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:50',
            'lastName' => 'nullable|string|max:50',
            'firstName' => 'nullable|string|max:50',
            'email' => 'required|email|max:250|unique:users,email',
            'username' => 'nullable|string|max:25|unique:users,username',
            'password' => 'required|string|min:8|confirmed',
            'matricule' => 'nullable|string|unique:users,matricule',
            'fonction' => 'nullable|string|max:100',
            'role' => 'required|in:agent-de-security,superviseur',
        ], $this->messages());

        User::create($validatedData);

        return redirect()->route('admin.enregistrement')->with('success', 'Utilisateur enregistré avec succès.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('admin.updateUser', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:50',
            'lastName' => 'nullable|string|max:50',
            'firstName' => 'nullable|string|max:50',
            'email' => 'required|email|max:250|unique:users,email,'.$user->id,
            'username' => 'nullable|string|max:25|unique:users,username,'.$user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'matricule' => 'nullable|string|unique:users,matricule,'.$user->id,
            'fonction' => 'nullable|string|max:100',
            'role' => 'required|in:admin,agent-de-security,superviseur',
        ], $this->messages());

        if (! $request->filled('password')) {
            unset($validatedData['password']);
        }

        if ($user->id === auth()->id() && $user->role === 'admin') {
            $validatedData['role'] = 'admin';
        }

        $user->update($validatedData);

        return redirect()->route('admin.users')->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function list_users()
    {
        $users = User::withTrashed()->get();

        return view('admin.users', compact('users'));
    }

    private function messages(): array
    {
        return [
            'email.unique' => 'Cette adresse email est déjà enregistrée.',
            'username.unique' => 'Ce nom d\'utilisateur est déjà enregistré.',
            'matricule.unique' => 'Ce matricule est déjà enregistré.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'role.required' => 'Veuillez sélectionner un rôle.',
            'role.in' => 'Le rôle sélectionné est invalide.',
        ];
    }
}
