<?php

use App\Http\Controllers\AgentDemandeController;
use App\Http\Controllers\ArchivageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnregistrementUser;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\SuperviseurDemandeController;
use App\Http\Controllers\updateInfos;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'home']);

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
    Route::get('/archivages', [ArchivageController::class, 'admin'])->name('admin.archivages');
});

// --- SUPERVISEUR ---
Route::middleware(['auth', 'role:superviseur'])->prefix('superviseur')->group(function () {
    Route::get('/', [DashboardController::class, 'superviseur'])->name('superviseur.dashboard');
    Route::get('/demandes', [SuperviseurDemandeController::class, 'index'])->name('superviseur.demandes.index');
    Route::get('/demandes/create', [SuperviseurDemandeController::class, 'create'])->name('superviseur.demandes.create');
    Route::post('/demandes', [SuperviseurDemandeController::class, 'store'])->name('superviseur.demandes.store');
    Route::get('/demandes/{id}', [SuperviseurDemandeController::class, 'show'])->name('superviseur.demandes.show');
    Route::get('/demandes/{id}/edit', [SuperviseurDemandeController::class, 'edit'])->name('superviseur.demandes.edit');
    Route::put('/demandes/{id}', [SuperviseurDemandeController::class, 'update'])->name('superviseur.demandes.update');
    Route::post('/demandes/{id}/envoyer', [SuperviseurDemandeController::class, 'envoyer'])->name('superviseur.demandes.envoyer');
    Route::post('/demandes/{id}/observation-acces', [SuperviseurDemandeController::class, 'observationAcces'])->name('superviseur.demandes.observation-acces');
    Route::post('/demandes/{id}/observation-sortie', [SuperviseurDemandeController::class, 'observationSortie'])->name('superviseur.demandes.observation-sortie');
    Route::get('/archivages', [ArchivageController::class, 'superviseur'])->name('superviseur.archivages');
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
    Route::get('/archivages', [ArchivageController::class, 'agent'])->name('agent.archivages');
    Route::patch('/notifications/{id}/lu', [DashboardController::class, 'marquerLu'])->name('agent.notifications.lu');
});
