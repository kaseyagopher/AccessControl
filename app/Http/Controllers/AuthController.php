<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Contrôleur d'authentification simple.
 *
 * - login       : connecter un utilisateur
 * - logout      : déconnecter l'utilisateur
 * - setupAdmin  : créer le premier admin (une seule fois)
 */
class AuthController extends Controller
{
    /** Affiche le formulaire de connexion */
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole();
        }

        return view('auth.login');
    }

    /** Traite la connexion */
    public function login(Request $request)
    {
        $request->validate([ 
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();

            return $this->redirectByRole();
        }

        return back()->withErrors([
            'email' => 'Email ou mot de passe incorrect.',
        ]);
    }

    /**
     * Crée le premier admin avec des données prédéfinies.
     * Appeler simplement : GET /setup-admin
     * Fonctionne une seule fois.
     */
    public function createAdmin()
    {
        if ($this->adminExists()) {
            return response('Un administrateur existe déjà.', 403);
        }

        User::create([
            'name' => 'Administrateur',
            'email' => 'admin@accesscontrol.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        return response(
            'Administrateur créé avec succès.' . PHP_EOL .
            'Email : admin@accesscontrol.com' . PHP_EOL .
            'Mot de passe : password' . PHP_EOL .
            'Connectez-vous sur /login',
            200
        );
    }

    /** Déconnexion */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /** Vérifie si un admin existe déjà en base */
    private function adminExists(): bool
    {
        return User::where('role', 'admin')->exists();
    }

    /** Redirige vers le bon tableau de bord selon le rôle */
    private function redirectByRole()
    {
        return match (Auth::user()->role) {
            'admin' => redirect('/admin'),
            'superviseur' => redirect('/superviseur'),
            'agent-de-security' => redirect('/agent-securite'),
            default => abort(403, 'Rôle non reconnu.'),
        };
    }
}
