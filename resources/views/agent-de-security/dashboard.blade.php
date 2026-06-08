@extends('layouts.simple')

@section('title', 'Agent de sécurité')

@section('content')
    <h1>Tableau de bord Agent de sécurité</h1>
    <p>Bienvenue {{ Auth::user()->name }} !</p>
    <p>Votre rôle : agent-de-security</p>
@endsection
