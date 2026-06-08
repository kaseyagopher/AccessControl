@extends('layouts.simple')

@section('title', 'Connexion')

@section('content')
    <h1>Connexion</h1>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <p>
            <label for="email">Email</label><br>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required>
        </p>
        @error('email')
            <p>{{ $message }}</p>
        @enderror

        <p>
            <label for="password">Mot de passe</label><br>
            <input type="password" name="password" id="password" required>
        </p>

        <button type="submit">Se connecter</button>
    </form>

    @if (! \App\Models\User::where('role', 'admin')->exists())
        <p>
            Première installation ?
            <a href="{{ route('setup.admin') }}">Créer l'administrateur</a>
        </p>
    @endif
@endsection
