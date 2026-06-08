@extends('layouts.simple')

@section('title', 'Admin')

@section('content')
    <h1>Tableau de bord Admin</h1>
    <p>Bienvenue {{ Auth::user()->name }} !</p>
    <p>Votre rôle : admin</p>
@endsection
