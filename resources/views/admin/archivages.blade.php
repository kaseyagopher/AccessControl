@extends('layouts.simple')

@section('title', 'Archivages')

@section('content')
<x-page-header title="Archivages" subtitle="Historique complet de toutes les visites" icon="clock" color="amber" />

<x-archivages-panel
    :filter-route="route('admin.archivages')"
    :show-superviseur="true"
/>
@endsection
