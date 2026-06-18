@extends('layouts.simple')

@section('title', 'Mon profil')

@section('content')
<x-page-header title="Mon profil" subtitle="Mettre à jour vos informations" icon="user" color="sky" />
@include('layouts.partials.profile-form', [
    'action' => route('superviseur.settings.update'),
    'user' => Auth::user(),
    'showRole' => false,
])
@endsection
