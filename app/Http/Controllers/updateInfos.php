<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


use App\Models\User;

class updateInfos extends Controller
{
    public function editAdminInfos()
    {
        $user = Auth::user();
        return view('admin.settings', compact('user'));
    }

    public function updateAdminInfos(Request $request)
    {
        $user = Auth::user();

        $validatedData = $request->validate([
            'name' => 'required|string|max:50',
            'lastName' => 'nullable|string|max:50', 
            'firstName' => 'nullable|string|max:50',
            'email' => 'required|email|max:250|unique:users,email,' . $user->id,
            'password' => 'nullable|string|max:50|min:5|confirmed',
            'matricule' => 'nullable|string|unique:users,matricule,' . $user->id,
            'role' => 'required|in:admin,agent-de-security,superviseur',
        ]);

        if ($request->filled('password')) {
            $validatedData['password'] = Hash::make($request->password);
        } else {
            unset($validatedData['password']);
        }

        $user->update($validatedData);

        return redirect()->back()->with('success', 'Informations mises à jour avec succès !');
    }

    public function destroy(Request $request, $id) 
    {
        $user = User::findOrFail($id); 

        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas vous supprimer vous-même !');
        }

        $user->forceDelete(); 

        return redirect()->back()->with('success', "L'utilisateur a bien été supprimé.");
    }

    public function disable(Request $request, $id)
    {
        $user = User::findOrFail($id); 
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas vous desactiver  vous-même !');
        }

        $user->delete();
        return redirect()->back()->with('success', "Compte desactivé avec success !.");

    }


    public function restore ($id)
    {
        $user = User::withTrashed()->findOrFail($id); 
        $user->restore();
        
        return redirect()->back()->with('success', "Compte réactivé avec success !.");

    }
}