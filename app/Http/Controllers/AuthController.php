<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showSignUp()
    {
        if(Auth::check()){
            return redirect()->route('dashboard');
        } else {
            return view('auth.register');
        }
    }

        public function showFormLogin()
    {
        if(Auth::check()){
            return redirect()->route('dashboard');
        } else {
            return view('auth.login');
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        
        if(Auth::attempt($request->only('email', 'password'))){
            return redirect()->route('dashboard');
        } else {
            return back()->withErrors([
                'email' => 'Mots de passe ou adresse incorrecte',
            ]);
        }
    }

    public function signUp(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:4|confirmed',
        ]);

        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make ($request->password),
        ]);

       
        return back()->with('succes', 'votre compte est bien crée');
    }

    public function logOut()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
