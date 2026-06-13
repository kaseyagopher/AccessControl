@extends('layouts.simple')

@section('title', 'Admin')

@section('content')
    <h1>Tableau de bord Admin</h1>
    <a class=" bg-blue-900 rounded-xl p-2  text-white" href="{{route('admim.enregistrement')}}">Creer un compte utilisateur </a>
    <a class=" bg-green-900 rounded-xl p-2  text-white" href="{{route('admin.users')}}">Liste des utilisateurs</a>
    <a class=" bg-yellow-900 rounded-xl p-2  text-white" href="{{route('admin.settings.edit')}}">Parametres du compte</a>
    <p>Bienvenue {{ Auth::user()->name }} !</p>
    <p>Votre rôle : admin</p>
@endsection
