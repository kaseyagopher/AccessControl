<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class updateInfos extends Controller

{

    public function editAdminInfos()
    {
        $user = Auth::user();
        return view('admin.settings', compact('user'));
    }
    public function updateAdminInfos(Request $request)
    {

        // dd("controller atteint");
        $user = Auth::user();

        $validatedData = $request->validate([
            'name' => 'required|string|max:50',
            'lastName' => 'string|max:50',
            'firstName' => 'string|max:50',
            'email' => 'required|email|max:250|unique:users,email,' . $user->id,
            'password' => 'nullable|string|max:50|min:5|confirmed',
            'matricule' => 'string|unique:users,matricule,' . $user->id,
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
}
