<?php

namespace App\Http\Controllers;

use Hash;
use Illuminate\Http\Request;
use App\Models\User;


class EnregistrementUser extends Controller
{
    public function store(Request $request)
    {
        
        $validatedData = $request ->validate([
            'name' => 'required|string|max:50',
            'lastName' => 'string|max:50',
            'firstName' => 'string|max:50',
            'email' => 'required|email|max:250|unique:users,email',
            'password' => 'required|string|max:50|min:5',
            'matricule' => 'string|unique:users,matricule',
            'role' => 'required|in:admin,agent-de-security,superviseur',

        ],
        [
            'email.unique'=>'Cette adresse email est deja enregistree ',
            'matricule.unique'=>'Ce matricule  est deja enregistre ',
            'password'=>'Le mot de passe doit avoir un minimum de 8 caracteres ',


        ]);
        
        $validatedData['password'] = Hash::make($request->password);
        User::create($validatedData);
        return redirect()->route('admim.enregistrement')->with('success', 'Utiliseur enregistré avec succes ! ');
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
            'lastName' => 'string|max:50',
            'firstName' => 'string|max:50',
            'email' => 'required|email|max:250|unique:users,email,' . $user->id,
            'password' => 'nullable|string|max:50|min:5',
            'matricule' => 'string|unique:users,matricule,' . $user->id,
            'role' => 'required|in:admin,agent-de-security,superviseur',
        ]);

        if ($request->filled('password')) {
            $validatedData['password'] = Hash::make($request->password);
        } else {
            unset($validatedData['password']);
        }

        $user->update($validatedData);
        return redirect()->route('admim.enregistrement')->with('success', 'Utilisateur mis à jour avec succès !');
    }

    public function list_users()
    {
        $users = User::withTrashed()->get();
        return view('admin.users', compact('users'));
    }
}
