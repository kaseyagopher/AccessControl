<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole();
        }

        return view('auth.login');
    }

    public function home()
    {
        if (Auth::check()) {
            return $this->redirectByRole();
        }

        return redirect()->route('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'L\'adresse email n\'est pas valide.',
            'password.required' => 'Le mot de passe est obligatoire.',
        ]);

        $user = User::withTrashed()->where('email', $request->email)->first();

        if ($user && $user->trashed()) {
            return back()->withErrors([
                'email' => 'Ce compte a été désactivé. Contactez un administrateur.',
            ])->onlyInput('email');
        }

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();

            return $this->redirectByRole();
        }

        return back()->withErrors([
            'email' => 'Email ou mot de passe incorrect.',
        ])->onlyInput('email');
    }

    public function createAdmin()
    {
        if (! app()->environment('local')) {
            abort(404);
        }

        if ($this->adminExists()) {
            return response('Un administrateur existe déjà.', 403);
        }

        User::create([
            'name' => 'Administrateur',
            'email' => 'admin@accesscontrol.com',
            'password' => 'password',
            'role' => 'admin',
        ]);

        return response(
            'Administrateur créé avec succès.'.PHP_EOL.
            'Email : admin@accesscontrol.com'.PHP_EOL.
            'Mot de passe : password'.PHP_EOL.
            'Connectez-vous sur /login',
            200
        );
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    private function adminExists(): bool
    {
        return User::where('role', 'admin')->exists();
    }

    private function redirectByRole()
    {
        return match (Auth::user()->role) {
            'admin' => redirect('/admin'),
            'superviseur' => redirect('/superviseur'),
            'agent-de-security' => redirect('/agent-de-security'),
            default => abort(403, 'Rôle non reconnu.'),
        };
    }
}
