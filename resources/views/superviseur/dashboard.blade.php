@extends('layouts.simple')

@section('title', 'Superviseur')

@section('content')
    <h1>Tableau de bord Superviseur</h1>
    <p>Bienvenue {{ Auth::user()->name }} !</p>
    <p>Votre rôle : superviseur</p>
@endsection
