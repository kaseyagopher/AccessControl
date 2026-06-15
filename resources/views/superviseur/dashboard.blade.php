@extends('layouts.simple')

@section('title', 'Superviseur')

@section('content')
    <h1>Tableau de bord Superviseur</h1>
    <p>Bienvenue {{ Auth::user()->name }} !</p>
    <p>Votre rôle : superviseur</p>
    <a class=" bg-yellow-900 rounded-xl p-2  text-white" href="{{route('superviseur.settings.edit')}}">Parametres du compte</a>

@endsection
