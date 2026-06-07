<?php

use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;




Route::get('/', function () {



    return view('welcome');
});

Route::get('/register', [\App\Http\Controllers\AuthController::class, 'showSignUp'])->name('register');
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'signUp'])->name('registration.register');

Route::get('/login', [\App\Http\Controllers\AuthController::class, 'showFormLogin'])->name('login');
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login.submit');

Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logOut'])->name('logout');

Route::get('/dashboard', [\App\Http\Controllers\AccessControlController::class, 'dashboard'])->name('dashboard');

