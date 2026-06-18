<?php

use App\Http\Controllers\AgentDemandeController;
use App\Http\Controllers\AgentLettreController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnregistrementUser;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\SuperviseurDemandeController;
use App\Http\Controllers\SuperviseurLettreController;
use App\Http\Controllers\updateInfos;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/setup-admin', [AuthController::class, 'createAdmin'])->name('setup.admin');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// --- ADMIN ---
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'admin'])->name('admin.dashboard');
    Route::get('/enregistrement', fn () => view('admin.formCreateUser'))->name('admin.enregistrement');
    Route::post('/enregistrement', [EnregistrementUser::class, 'store']);
    Route::get('/update-user/{id}', [EnregistrementUser::class, 'edit'])->name('admin.update-user');
    Route::put('/update-user/{id}', [EnregistrementUser::class, 'update']);
    Route::get('/users', [EnregistrementUser::class, 'list_users'])->name('admin.users');
    Route::get('/settings', [updateInfos::class, 'editAdminInfos'])->name('admin.settings.edit');
    Route::put('/settings', [updateInfos::class, 'updateAdminInfos'])->name('admin.settings.update');
    Route::delete('/settings/delete/{id}', [updateInfos::class, 'destroy'])->name('admin.users.destroy');
    Route::delete('/settings/users/{id}/disable', [updateInfos::class, 'disable'])->name('admin.users.disable');
    Route::patch('/settings/users/{id}/restore', [updateInfos::class, 'restore'])->name('admin.users.restore');
    Route::get('/rapports', [RapportController::class, 'index'])->name('admin.rapports');
    Route::get('/rapports/export-excel', [RapportController::class, 'exportExcel'])->name('admin.rapports.excel');
    Route::get('/rapports/export-pdf', [RapportController::class, 'exportPdf'])->name('admin.rapports.pdf');
});

// --- SUPERVISEUR ---
Route::middleware(['auth', 'role:superviseur'])->prefix('superviseur')->group(function () {
    Route::get('/', [DashboardController::class, 'superviseur'])->name('superviseur.dashboard');
    Route::get('/settings', [updateInfos::class, 'editSuperviseurInfos'])->name('superviseur.settings.edit');
    Route::put('/settings', [updateInfos::class, 'updateInfos'])->name('superviseur.settings.update');
    Route::get('/demandes', [SuperviseurDemandeController::class, 'index'])->name('superviseur.demandes.index');
    Route::get('/demandes/create', [SuperviseurDemandeController::class, 'create'])->name('superviseur.demandes.create');
    Route::post('/demandes', [SuperviseurDemandeController::class, 'store'])->name('superviseur.demandes.store');
    Route::get('/historique', [SuperviseurDemandeController::class, 'historique'])->name('superviseur.historique');
    Route::get('/lettres', [SuperviseurLettreController::class, 'index'])->name('superviseur.lettres.index');
    Route::get('/lettres/create', [SuperviseurLettreController::class, 'create'])->name('superviseur.lettres.create');
    Route::post('/lettres', [SuperviseurLettreController::class, 'store'])->name('superviseur.lettres.store');
    Route::patch('/notifications/{id}/lu', [DashboardController::class, 'marquerLu'])->name('superviseur.notifications.lu');
});

// --- AGENT DE SÉCURITÉ ---
Route::middleware(['auth', 'role:agent-de-security'])->prefix('agent-de-security')->group(function () {
    Route::get('/', [DashboardController::class, 'agent'])->name('agent-de-security.dashboard');
    Route::get('/demandes', [AgentDemandeController::class, 'index'])->name('agent.demandes.index');
    Route::get('/demandes/{id}', [AgentDemandeController::class, 'show'])->name('agent.demandes.show');
    Route::post('/demandes/{id}/valider', [AgentDemandeController::class, 'valider'])->name('agent.demandes.valider');
    Route::post('/demandes/{id}/refuser', [AgentDemandeController::class, 'refuser'])->name('agent.demandes.refuser');
    Route::get('/visites-aujourdhui', [AgentDemandeController::class, 'visitesDuJour'])->name('agent.visites.aujourdhui');
    Route::post('/demandes/{id}/arrivee', [AgentDemandeController::class, 'confirmerArrivee'])->name('agent.demandes.arrivee');
    Route::post('/demandes/{id}/sortie', [AgentDemandeController::class, 'enregistrerSortie'])->name('agent.demandes.sortie');
    Route::get('/lettres', [AgentLettreController::class, 'index'])->name('agent.lettres.index');
    Route::get('/lettres/{id}', [AgentLettreController::class, 'show'])->name('agent.lettres.show');
    Route::post('/lettres/{id}/valider', [AgentLettreController::class, 'valider'])->name('agent.lettres.valider');
    Route::post('/lettres/{id}/refuser', [AgentLettreController::class, 'refuser'])->name('agent.lettres.refuser');
    Route::patch('/notifications/{id}/lu', [DashboardController::class, 'marquerLu'])->name('agent.notifications.lu');
});
