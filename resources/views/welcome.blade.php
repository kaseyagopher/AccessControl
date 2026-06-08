@extends('layouts.simple')

@section('title', 'Accueil')

@section('content')
    <h1>AccessControl</h1>
    <p>Application d'authentification simple avec gestion des rôles.</p>

    @auth
        <p>
            <a href="{{
                match (Auth::user()->role) {
                    'admin' => '/admin',
                    'superviseur' => '/superviseur',
                    'agent-de-security' => '/agent-de-security',
                    default => '/',
                }
            }}">Mon tableau de bord</a>
        </p>
    @else
        <p><a href="{{ route('login') }}">Connexion</a></p>
    @endauth
@endsection
