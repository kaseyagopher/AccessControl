@extends('layouts.simple')

@section('title', 'Archivages')

@section('content')
<x-page-header title="Archivages" subtitle="Consultez l'historique de toutes les visites traitées" icon="clock" color="amber" />

<x-archivages-panel
    :filter-route="route('agent.archivages')"
    :show-superviseur="true"
    detail-route="agent.demandes.show"
/>
@endsection
