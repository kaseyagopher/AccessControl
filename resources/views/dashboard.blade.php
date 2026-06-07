@extends('base')

@section('title', '')

@section('content')

    <form action="{{route('logout')}}" method="POST">

        @csrf
        <button type="submit"  >  Se deconnecter</button>
        
    </form>

    <h1 class="text-xl font-bold"> Bienvenue {{($user->name)}} </h1>

@endsection