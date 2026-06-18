<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class updateInfos extends Controller
{
    public function editAdminInfos()
    {
        return view('admin.editProfil', ['user' => Auth::user()]);
    }

    public function editSuperviseurInfos()
    {
        return view('superviseur.editProfil', ['user' => Auth::user()]);
    }

    public function updateAdminInfos(Request $request)
    {
        return $this->updateInfos($request);
    }

    public function updateInfos(Request $request)
    {
        $user = Auth::user();

        $validatedData = $request->validate([
            'name' => 'required|string|max:50',
            'lastName' => 'nullable|string|max:50',
            'firstName' => 'nullable|string|max:50',
            'email' => 'required|email|max:250|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'matricule' => 'nullable|string|unique:users,matricule,'.$user->id,
            'role' => 'required|in:admin,agent-de-security,superviseur',
        ], [
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'matricule.unique' => 'Ce matricule est déjà utilisé.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        // Empêcher un admin de se retirer son propre rôle par erreur
        if ($user->role === 'admin') {
            $validatedData['role'] = 'admin';
        }

        if (! $request->filled('password')) {
            unset($validatedData['password']);
        }

        $user->update($validatedData);

        return redirect()->back()->with('success', 'Informations mises à jour avec succès.');
    }

    public function destroy(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas vous supprimer vous-même.');
        }

        $user->forceDelete();

        return redirect()->back()->with('success', "L'utilisateur a bien été supprimé.");
    }

    public function disable(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas désactiver votre propre compte.');
        }

        $user->delete();

        return redirect()->back()->with('success', 'Compte désactivé avec succès.');
    }

    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->back()->with('success', 'Compte réactivé avec succès.');
    }
}
