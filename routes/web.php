<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EnregistrementUser;
use App\Http\Controllers\updateInfos;
use Illuminate\Support\Facades\Route;

// Page d'accueil
Route::get('/', function () {
    return view ('welcome');
});

// --- CRÉATION DU PREMIER ADMIN (une seule fois, sans formulaire) ---
Route::get('/setup-admin', [AuthController::class, 'createAdmin'])->name('setup.admin');

// --- CONNEXION UNIQUEMENT (pas d'inscription publique) ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Déconnexion
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// --- ZONES PROTÉGÉES PAR RÔLE ---
// Rôles : admin | superviseur | agent-de-security

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/enregistrement', function (){
        return view('admin.formCreateUser');
    })->name('admim.enregistrement');

    Route::post('/enregistrement', [EnregistrementUser::class, 'store'])->name('admim.enregistrement');
    Route::get('/update-user/{id}', [EnregistrementUser::class, 'edit'])->name('admin.update-user');
    Route::put('/update-user/{id}', [EnregistrementUser::class, 'update'])->name('admin.update-user');
    Route::get('/users', [EnregistrementUser::class, 'list_users'])->name('admin.users');
    Route::get('/settings', [updateInfos::class, 'editAdminInfos'])->name('admin.settings.edit');
    Route::put('/settings', [updateInfos::class, 'updateAdminInfos'])->name('admin.settings.update');
    Route::delete('/settings/delete/{id}', [updateInfos::class, 'destroy'])->name('admin.users.destroy');
});

Route::middleware(['auth', 'role:superviseur'])->group(function () {
    Route::get('/superviseur', function () {
        return view('superviseur.dashboard');
    })->name('superviseur.dashboard');
    //ici Franck tu ajoutera les routes pour les superviseurs
});

Route::middleware(['auth', 'role:agent-de-security'])->group(function () {
    Route::get('/agent-de-security', function () {
        return view('agent-de-security.dashboard');
    })->name('agent-de-security.dashboard');
    //ici Franck tu ajoutera les routes pour les agents de security
});
