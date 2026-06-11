@extends('layouts.simple')

@section('title', 'Admin')

@section('content')
    <h1>Tableau de bord Admin</h1>
    <a class=" bg-blue-900 rounded-xl p-2  text-white" href="{{route('admim.enregistrement')}}">Creer un compte utilisateur </a>
    <p>Bienvenue {{ Auth::user()->name }} !</p>
    <p>Votre rôle : admin</p>
@endsection
