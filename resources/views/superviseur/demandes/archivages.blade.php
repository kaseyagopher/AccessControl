@extends('layouts.simple')

@section('title', 'Archivages')

@section('content')
<x-page-header title="Archivages" subtitle="Consultez et filtrez l'historique de vos visites" icon="clock" color="amber" />

<x-archivages-panel
    :filter-route="route('superviseur.archivages')"
    :demandes="$demandes"
    :annees="$annees"
/>
@endsection
